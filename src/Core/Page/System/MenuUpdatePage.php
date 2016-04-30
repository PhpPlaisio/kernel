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
  private $details;

  /**
   * The ID of the menu entry.
   *
   * @var int
   */
  private $mnuId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->mnuId         = self::getCgiId('mnu', 'mnu');
    $this->details       = Abc::$DL->systemMenuGetDetails($this->mnuId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $mnuId
   *
   * @return string
   */
  public static function getUrl($mnuId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_MENU_MODIFY, 'pag');
    $url .= self::putCgiVar('mnu', $mnuId, 'mnu');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the menu entry.
   */
  protected function databaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately of no changes are submitted.
    if (empty($changes)) return;

    if ($values['mnu_title'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->usrId,
                                         C::WDG_ID_MENU,
                                         false,
                                         false,
                                         $values['mnu_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemMenuUpdate($this->mnuId,
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
    $this->form->setValues($this->details);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
