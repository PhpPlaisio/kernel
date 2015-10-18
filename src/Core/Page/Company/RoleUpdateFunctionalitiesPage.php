<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\CompanyRoleUpdateFunctionalitiesSlatControlFactory;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for modifying the granted functionalities to a role.
 */
class RoleUpdateFunctionalitiesPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $myForm;

  /**
   * The ID of the role.
   *
   * @var int
   */
  private $myRolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myRolId = self::getCgiVar('rol', 'rol');

    $this->myDetails = Abc::$DL->companyRoleGetDetails($this->myActCmpId, $this->myRolId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theCmpId The ID of the target company
   * @param int $theRolId The ID of role to be modified.
   *
   * @return string
   */
  public static function getUrl($theCmpId, $theRolId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_ROLE_UPDATE_FUNCTIONALITIES, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');
    $url .= self::putCgiVar('rol', $theRolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $method = $this->myForm->execute();
    if ($method) $this->$method();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    // Get all available functionalities.
    $pages = Abc::$DL->companyRoleGetAvailableFunctionalities($this->myActCmpId, $this->myRolId, $this->myLanId);

    // Create form.
    $this->myForm = new CoreForm();

    // Add field set.
    $field_set = $this->myForm->createFieldSet();

    // Create factory.
    $factory = new CompanyRoleUpdateFunctionalitiesSlatControlFactory($this->myLanId);
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = $button->createFormControl('submit', 'submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_UPDATE));
    $this->myForm->addSubmitHandler($button, 'handleForm');

    // Put everything together in a LouverControl.
    $louver = new LouverControl('data');
    $louver->setAttrClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($pages);
    $louver->populate();

    // Add the lover control to the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Grants and revokes functionalities from the role.
   */
  private function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (!$changes) return;

    foreach ($changes['data'] as $fun_id => $dummy)
    {
      if ($values['data'][$fun_id]['fun_enabled'])
      {
        Abc::$DL->companyRoleInsertFunctionality($this->myActCmpId, $this->myRolId, $fun_id);
      }
      else
      {
        Abc::$DL->companyRoleDeleteFunctionality($this->myActCmpId, $this->myRolId, $fun_id);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->databaseAction();

    Http::redirect(RoleDetailsPage::getUrl($this->myActCmpId, $this->myRolId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

