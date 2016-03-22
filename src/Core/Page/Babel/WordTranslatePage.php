<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for translating a single word.
 */
class WordTranslatePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word.
   *
   * @var array
   */
  protected $myDetails;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  /**
   * The ID of the word to be translated.
   *
   * @var int
   */
  protected $myWrdId;

  /**
   * The URL to return after the word has been translated.
   *
   * @var string
   */
  private $myRedirectUrl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWrdId = self::getCgiId('wrd', 'wrd');

    $this->myRedirectUrl = self::getCgiUrl('redirect');

    $this->myDetails = Abc::$DL->WordGetDetails($this->myWrdId, $this->myActLanId);

    if (!$this->myRedirectUrl)
    {
      $this->myRedirectUrl = WordGroupDetailsPage::getUrl($this->myDetails['wdg_id'], $this->myActLanId);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int         $theWrdId       The ID of the word to be translated.
   * @param int         $theLanId       The ID of the target language.
   * @param string|null $theRedirectUrl If set the URL to redirect the user agent after the word has been translated.
   *
   * @return string
   */
  public static function getUrl($theWrdId, $theLanId, $theRedirectUrl = null)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_TRANSLATE, 'pag');
    $url .= self::putCgiVar('wrd', $theWrdId, 'wrd');
    $url .= self::putCgiVar('act_lan', $theLanId, 'lan');
    $url .= self::putCgiVar('redirect', $theRedirectUrl);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoTabContent()
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $ref_language = Abc::$DL->LanguageGetName($this->myRefLanId, $this->myRefLanId);
    $act_language = Abc::$DL->LanguageGetName($this->myActLanId, $this->myRefLanId);

    $this->myForm = new CoreForm();

    // Show word group name.
    $input = $this->myForm->createFormControl('span', 'word_group', 'Word Group');
    $input->setInnerText($this->myDetails['wdg_name']);

    // Show word group ID
    $input = $this->myForm->createFormControl('span', 'wrd_id', 'ID');
    $input->setInnerText($this->myDetails['wdg_id']);

    // Show label
    $input = $this->myForm->createFormControl('span', 'label', 'Label');
    $input->setInnerText($this->myDetails['wrd_label']);

    // Show comment.
    $input = $this->myForm->createFormControl('span', 'comment', 'Comment');
    $input->setInnerText($this->myDetails['wrd_comment']);

    // Show data
    // @todo Show data.

    // Show word in reference language.
    $input = $this->myForm->createFormControl('span', 'ref_language', $ref_language);
    $input->setInnerText(Babel::getWord($this->myWrdId /*, $this->myRefLanId*/)); // @todo show word in ref lan.

    // Create form control for the actual word.
    $input = $this->myForm->createFormControl('text', 'wdt_text', $act_language, true);
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $input->setValue($this->myDetails['wdt_text']);

    // Create a submit button.
    $this->myForm->addSubmitButton(C::WRD_ID_BUTTON_TRANSLATE, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translation of the word in the target language.
   */
  private function dataBaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    // Return immediately when no form controls are changed.
    if (empty($changes)) return;

    Abc::$DL->wordTranslateWord($this->myUsrId, $this->myWrdId, $this->myActLanId, $values['wdt_text']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->myForm->execute();
    switch ($method)
    {
      case null;
        // Nothing to do.
        break;

      case  'handleForm':
        $this->handleForm();
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->dataBaseAction();

    Http::redirect($this->myRedirectUrl);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

