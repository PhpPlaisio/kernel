<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a page group.
 */
class TabUpdatePage extends TabBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the page group.
   * 
   * @var array
   */
  private $myDetails;

  /**
   * The ID of the page group.
   * 
   * @var int
   */
  private $myPtbId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myPtbId       = self::getCgiId('ptb', 'ptb');
    $this->myDetails     = Abc::$DL->systemTabGetDetails($this->myPtbId, $this->myLanId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $thePtbId The ID of the page tab.
   *
   * @return string
   */
  public static function getUrl($thePtbId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_TAB_UPDATE, 'pag');
    $url .= self::putCgiVar('ptb', $thePtbId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of a page group.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;


    if ($values['ptb_title'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId,
                                         C::WDG_ID_PAGE_GROUP_TITLE,
                                         false,
                                         false,
                                         $values['ptb_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemTabUpdateDetails($this->myPtbId, $wrd_id, $values['ptb_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $values = $this->myDetails;
    unset($values['ptb_title']);

    $this->myForm->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

