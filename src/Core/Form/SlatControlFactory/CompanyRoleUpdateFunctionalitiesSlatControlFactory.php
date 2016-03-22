<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat joints for updating enabled functionalities.
 */
class CompanyRoleUpdateFunctionalitiesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for function IDs.
   *
   * @var Obfuscator
   */
  private $myFunIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with name of module.
    $table_column = new TextTableColumn('Module', 'mdl_name');
    $col          = $this->addSlatJoint('mdl_name', new TableColumnSlatJoint($table_column));
    $col->setSortOrder(1);

    // Create slat joint for table column with name of functionality.
    $table_column = new TextTableColumn('Functionality', 'fun_name');
    $col          = $this->addSlatJoint('fun_name', new TableColumnSlatJoint($table_column));
    $col->setSortOrder(2);

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('fun_enabled', $table_column);

    $this->myFunIdObfuscator = Abc::getObfuscator('fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($theLouverControl, $theData)
  {
    /** @var SlatControl $row */
    $row = $theLouverControl->addFormControl(new SlatControl($theData['fun_id']));
    $row->setObfuscator($this->myFunIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($theData);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'fun_name');
    $control->setValue($theData);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'fun_enabled');
    $control->setValue($theData['fun_enabled']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
