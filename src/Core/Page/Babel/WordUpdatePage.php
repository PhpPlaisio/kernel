<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a word.
 */
class WordUpdatePage extends WordBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word.
   * 
   * @var array
   */
  private $myDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWrdId       = self::getCgiId('wrd', 'wrd');
    $this->myDetails     = Abc::$DL->wordGetDetails($this->myWrdId, $this->myActLanId);
    $this->myWdgId       = $this->myDetails['wdg_id'];
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int         $theWrdId       The ID of the word.
   * @param string|null $theRedirectUrl If set the URL to redirect the user agent.
   *
   * @return string
   */
  public static function getUrl($theWrdId, $theRedirectUrl = null)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_UPDATE, 'pag');
    $url .= self::putCgiVar('wrd', $theWrdId, 'wrd');
    $url .= self::putCgiVar('redirect', $theRedirectUrl);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the word.
   */
  public function databaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    // Return immediately when no form controls are changed.
    if (!$changes) return;

    Abc::$DL->wordUpdateDetails($this->myWrdId,
                                $values['wdg_id'],
                                $values['wrd_label'],
                                $values['wrd_comment'],
                                $values['wdt_text']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function setValues()
  {
    $this->myForm->setValues($this->myDetails);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

