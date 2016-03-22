<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a role.
 */
class RoleUpdatePage extends RoleBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the role.
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

    $this->myActCmpId    = self::getCgiId('cmp', 'cmp');
    $this->myRolId       = self::getCgiId('rol', 'rol');
    $this->myDetails     = Abc::$DL->companyRoleGetDetails($this->myActCmpId, $this->myRolId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theCmpId The ID of the target company.
   * @param int $theRolId The ID of role to be modified.
   *
   * @return string
   */
  public static function getUrl($theCmpId, $theRolId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_ROLE_UPDATE, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');
    $url .= self::putCgiVar('rol', $theRolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Update the details of the role.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    Abc::$DL->companyRoleUpdate($this->myActCmpId, $this->myRolId, $values['rol_name'], $values['rol_weight']);
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

