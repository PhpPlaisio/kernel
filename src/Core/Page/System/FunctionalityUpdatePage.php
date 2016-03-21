<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a functionality.
 */
class FunctionalityUpdatePage extends FunctionalityBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the functionality.
   * 
   * @var array
   */
  private $myDetails;

  /**
   * The ID of the functionality.
   * 
   * @var int
   */
  private $myFunId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myFunId       = self::getCgiId('fun', 'fun');
    $this->myDetails     = Abc::$DL->systemFunctionalityGetDetails($this->myFunId, $this->myLanId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $theFunId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl($theFunId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE, 'pag');
    $url .= self::putCgiVar('fun', $theFunId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the functionality.
   */
  protected function dataBaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (!$changes) return;

    if ($values['fun_name'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId,
                                         C::WDG_ID_FUNCTIONALITIES,
                                         false,
                                         false,
                                         $values['fun_name']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemFunctionalityUpdateDetails($this->myFunId, $values['mdl_id'], $wrd_id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $values = $this->myDetails;
    unset($values['fun_name']);

    $this->myForm->mergeValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
