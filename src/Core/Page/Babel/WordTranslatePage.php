<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

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

    if (!isset($this->myRedirectUrl))
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
    $input = new SpanControl('word_group');
    $input->setInnerText($this->myDetails['wdg_name']);
    $this->myForm->addFormControl($input, 'Word Group');

    // Show word group ID
    $input = new SpanControl('wrd_id');
    $input->setInnerText($this->myDetails['wdg_id']);
    $this->myForm->addFormControl($input, 'ID Group');

    // Show label
    $input = new SpanControl('label');
    $input->setInnerText($this->myDetails['wrd_label']);
    $this->myForm->addFormControl($input, 'Label');

    // Show comment.
    $input = new SpanControl('comment');
    $input->setInnerText($this->myDetails['wrd_comment']);
    $this->myForm->addFormControl($input, 'Comment');

    // Show data
    // @todo Show data.

    // Show word in reference language.
    $input = new SpanControl('ref_language');
    $input->setInnerText(Babel::getWord($this->myWrdId /*, $this->myRefLanId*/)); // @todo show word in ref lan.
    $this->myForm->addFormControl($input, $ref_language);

    // Create form control for the actual word.
    $input = new TextControl('wdt_text');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $input->setValue($this->myDetails['wdt_text']);
    $this->myForm->addFormControl($input, $act_language, true);

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
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->myForm->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->dataBaseAction();

    HttpHeader::redirectSeeOther($this->myRedirectUrl);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

