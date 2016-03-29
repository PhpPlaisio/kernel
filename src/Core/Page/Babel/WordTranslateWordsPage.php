<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\BabelWordTranslateSlatControlFactory;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for translating all words in a word group.
 */
class WordTranslateWordsPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the words.
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
   * The ID of the word group.
   *
   * @var int
   */
  protected $myWdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWdgId = self::getCgiId('wdg', 'wdg');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theWdgId The ID of the word group.
   * @param int $theLanId The target language.
   *
   * @return string
   */
  public static function getUrl($theWdgId, $theLanId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_TRANSLATE_WORDS, 'pag');
    $url .= self::putCgiVar('wdg', $theWdgId, 'wdg');
    $url .= self::putCgiVar('act_lan', $theLanId, 'lan');

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
    $words = Abc::$DL->wordGroupGetAllWordsTranslator($this->myWdgId, $this->myActLanId);

    $this->myForm = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->myForm->addFieldSet($field_set);

    // Create factory.
    $factory = new BabelWordTranslateSlatControlFactory($this->myRefLanId, $this->myActLanId);
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = new SubmitControl('submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_TRANSLATE));
    $button->addFormControl($submit);
    $this->myForm->addSubmitHandler($button, 'handleForm');

    // Put everything together in a LoverControl.
    $louver = new LouverControl('data');
    $louver->setAttrClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($words);
    $louver->populate();

    // Add the LouverControl the the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translations of the words in the target language.
   */
  private function databaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    foreach ($changes['data'] as $wrd_id => $changed)
    {
      Abc::$DL->wordTranslateWord($this->myUsrId, $wrd_id, $this->myActLanId, $values['data'][$wrd_id]['act_wdt_text']);
    }
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
    $this->databaseAction();

    Http::redirect(WordGroupDetailsPage::getUrl($this->myWdgId, $this->myActLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

