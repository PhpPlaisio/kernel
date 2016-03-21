<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a target page.
 */
class PageUpdatePage extends PageBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the target page.
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

    $this->myTargetPagId = self::getCgiId('tar_pag', 'pag');
    $this->myDetails     = Abc::$DL->systemPageGetDetails($this->myTargetPagId, $this->myLanId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $thePagId
   *
   * @return string
   */
  public static function getUrl($thePagId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_PAGE_UPDATE, 'pag');
    $url .= self::putCgiVar('tar_pag', $thePagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (!$changes) return;

    if ($values['pag_title'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId,
                                         C::WDG_ID_PAGE_TITLE,
                                         false,
                                         false,
                                         $values['pag_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemPageUpdateDetails($this->myTargetPagId,
                                      $wrd_id,
                                      $values['ptb_id'],
                                      $values['pag_id_org'],
                                      $values['mnu_id'],
                                      $values['pag_alias'],
                                      $values['pag_class'],
                                      $values['pag_label'],
                                      $values['pag_weight']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $values = $this->myDetails;
    unset($values['pag_title']);

    $this->myForm->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

