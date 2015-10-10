<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for updating the functionality that grant access to a page.
 */
class SystemPageUpdateFunctionalitiesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with module ID.
    $table_column = new NumericTableColumn('ID', 'mdl_id');
    $this->addSlatJoint('mdl_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of module.
    $table_column = new TextTableColumn('Name', 'mdl_name');
    $this->addSlatJoint('mdl_name', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with functionality ID.
    $table_column = new NumericTableColumn('ID', 'fun_id');
    $this->addSlatJoint('fun_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of functionality.
    $table_column = new TextTableColumn('Name', 'fun_name');
    $this->addSlatJoint('fun_name', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('fun_checked', $table_column);

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

    $control = $this->createFormControl($row, 'mdl_id');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'fun_id');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'fun_name');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'fun_checked');
    $control->setValue($theData['fun_checked']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
