<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a menu entry.
 */
class MenuUpdatePage extends MenuBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the menu entry.
   *
   * @var array
   */
  private $myDetails;

  /**
   * The ID of the menu entry.
   *
   * @var int
   */
  private $myMnuId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myMnuId       = self::getCgiId('mnu', 'mnu');
    $this->myDetails     = Abc::$DL->systemMenuGetDetails($this->myMnuId, $this->myLanId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $theMnuId
   *
   * @return string
   */
  public static function getUrl($theMnuId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_MENU_MODIFY, 'pag');
    $url .= self::putCgiVar('mnu', $theMnuId, 'mnu');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the menu entry.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately of no changes are submitted.
    if (empty($changes)) return;

    if ($values['mnu_title'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId,
                                         C::WDG_ID_MENU,
                                         false,
                                         false,
                                         $values['mnu_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemMenuUpdate($this->myMnuId,
                               $wrd_id,
                               $values['pag_id'],
                               $values['mnu_level'],
                               $values['mnu_group'],
                               $values['mnu_weight'],
                               $values['mnu_link']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $this->myForm->setValues($this->myDetails);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
