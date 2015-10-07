<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a menu entry.
 */
class MenuInsertPage extends MenuBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return '/pag/'.Abc::obfuscate(C::PAG_ID_SYSTEM_MENU_INSERT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a menu entry.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately of no changes are submitted.
    if (!$changes) return;

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

    Abc::$DL->systemMenuInsert($wrd_id,
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
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
