<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
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
  private $myRetUrl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWrdId = self::getCgiVar('wrd', 'wrd');

    $this->myRetUrl = self::getCgiUrl('redirect');

    $this->myDetails = Abc::$DL->WordGetDetails($this->myWrdId, $this->myActLanId);

    if (!$this->myRetUrl)
    {
      $this->myRetUrl = WordGroupDetailsPage::getUrl($this->myDetails['wdg_id'], $this->myActLanId);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int    $theWrdId
   * @param int    $theLanId
   * @param string $theRetUrl
   *
   * @return string
   */
  public static function getUrl($theWrdId, $theLanId, $theRetUrl = null)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_BABEL_WORD_TRANSLATE, 'pag');
    $url .= '/wrd/'.Abc::obfuscate($theWrdId, 'wrd');
    $url .= "/?act_lan_id=$theLanId";
    if ($theRetUrl) $url .= ";redirect=".urlencode($theRetUrl);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoTabContent()
  {
    $this->createForm();

    if ($this->myForm->isSubmitted('submit'))
    {
      $this->myForm->loadSubmittedValues();
      $valid = $this->myForm->validate();
      if (!$valid)
      {
        $this->echoForm();
      }
      else
      {
        $this->databaseAction();

        Http::redirect($this->myRetUrl);
      }
    }
    else
    {
      $this->echoForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $ref_language = Abc::$DL->LanguageGetName($this->myRefLanId, $this->myRefLanId);
    $act_language = Abc::$DL->LanguageGetName($this->myActLanId, $this->myRefLanId);

    $this->myForm = new CoreForm($this->myLanId);

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
    $this->myForm->addButtons(Babel::getWord(C::WRD_ID_BUTTON_OK));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translation of the word in the target language.
   */
  private function databaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    // Return immediately when no form controls are changed.
    if (!$changes) return;

    Abc::$DL->wordTranslateWord($this->myUsrId, $this->myWrdId, $this->myActLanId, $values['wdt_text']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the form shown on this page.
   */
  private function echoForm()
  {
    echo $this->myForm->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

