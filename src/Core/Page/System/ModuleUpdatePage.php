<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for modifying the details of a module.
 */
class ModuleUpdatePage extends ModuleBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var array The details of the module.
   */
  private $myDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myMdlId       = self::getCgiId('mdl', 'mdl');
    $this->myDetails     = Abc::$DL->systemModuleGetDetails($this->myMdlId, $this->myLanId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $theMdlId The ID of the module.
   *
   * @return string
   */
  public static function getUrl($theMdlId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_MODULE_UPDATE, 'pag');
    $url .= self::putCgiVar('mdl', $theMdlId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the module.
   */
  protected function dataBaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['mdl_name'])
    {
      // New module name. Insert word en retrieve wrd_id of the new word.
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId,
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
    Abc::$DL->systemModuleModify($this->myMdlId, $wrd_id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $values = $this->myDetails;
    unset($values['mdl_name']);

    $this->myForm->mergeValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
