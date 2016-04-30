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
  private $details;

  /**
   * The ID of the functionality.
   *
   * @var int
   */
  private $funId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId       = self::getCgiId('fun', 'fun');
    $this->details     = Abc::$DL->systemFunctionalityGetDetails($this->funId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl($funId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE, 'pag');
    $url .= self::putCgiVar('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the functionality.
   */
  protected function dataBaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['fun_name'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->usrId,
                                         C::WDG_ID_FUNCTIONALITIES,
                                         false,
                                         false,
                                         $values['fun_name']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->systemFunctionalityUpdateDetails($this->funId, $values['mdl_id'], $wrd_id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $values = $this->details;
    unset($values['fun_name']);

    $this->form->mergeValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
