<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a module.
 */
class ModuleInsertPage extends ModuleBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->buttonWrdId = C::WRD_ID_BUTTON_INSERT;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiVar('pag', C::PAG_ID_SYSTEM_MODULE_INSERT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a module.
   */
  protected function dataBaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['mdl_name'])
    {
      // New module name. Insert word en retrieve wrd_id of the new word.
      $wrd_id = Abc::$DL->wordInsertWord($this->usrId,
                                         C::WDG_ID_MODULE,
                                         false,
                                         false,
                                         $values['mdl_name']);
    }
    else
    {
      // Reuse of exiting module name.
      $wrd_id = $values['wrd_id'];
    }

    // Create the new module in the database.
    $this->mdlId = Abc::$DL->systemModuleInsert($wrd_id);
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
