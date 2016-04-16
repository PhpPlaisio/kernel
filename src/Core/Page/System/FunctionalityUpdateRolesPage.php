<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\SystemFunctionalityUpdateRolesSlatControlFactory;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\Http;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for granting/revoking access to/from a functionality to roles.
 */
class FunctionalityUpdateRolesPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the functionality.
   *
   * @var array
   */
  private $myDetails;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $myForm;

  /**
   * The ID of the functionality of which the pages that belong to it will be modified.
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

    $this->myFunId = self::getCgiId('fun', 'fun');

    $this->myDetails = Abc::$DL->systemFunctionalityGetDetails($this->myFunId, $this->myLanId);

    $this->appendPageTitle($this->myDetails['fun_name']);
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
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE_ROLES, 'pag');
    $url .= self::putCgiVar('fun', $theFunId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showFunctionality();

    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    // Get all available pages.
    $pages = Abc::$DL->systemFunctionalityGetAvailableRoles($this->myFunId);

    // Create form.
    $this->myForm = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->myForm->addFieldSet($field_set);

    // Create factory.
    $factory = new SystemFunctionalityUpdateRolesSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = new SubmitControl('submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_UPDATE));
    $button->addFormControl($submit);
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
   * Handles the form submit, i.e. add or removes pages to the functionality.
   */
  private function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    foreach ($changes['data'] as $rol_id => $dummy)
    {
      if ($values['data'][$rol_id]['rol_enabled'])
      {
        Abc::$DL->companyRoleInsertFunctionality($values['data'][$rol_id]['cmp_id'], $rol_id, $this->myFunId);
      }
      else
      {
        Abc::$DL->companyRoleDeleteFunctionality($values['data'][$rol_id]['cmp_id'], $rol_id, $this->myFunId);
      }
    }

    // Use brute force to proper profiles.
    Abc::$DL->profileProper();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->myForm->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->myForm->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->databaseAction();

    Http::redirect(FunctionalityDetailsPage::getUrl($this->myFunId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showFunctionality()
  {

    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    NumericTableRow::addRow($table, 'ID', $this->myDetails['fun_id'], '%d');

    // Add row for the module name to which the function belongs.
    TextTableRow::addRow($table, 'Module', $this->myDetails['mdl_name']);

    // Add row for the name of the function.
    TextTableRow::addRow($table, 'Functionality', $this->myDetails['fun_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
