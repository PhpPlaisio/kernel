<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a functionality.
 */
class FunctionalityInsertPage extends FunctionalityBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return '/pag/'.Abc::obfuscate(C::PAG_ID_SYSTEM_FUNCTIONALITY_INSERT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a functionality.
   */
  protected function dataBaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (!$changes) return;

    if ($values['fun_name'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId, C::WDG_ID_FUNCTIONALITIES, false, false, $values['fun_name']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemFunctionalityInsertDetails($values['mdl_id'], $wrd_id);
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
