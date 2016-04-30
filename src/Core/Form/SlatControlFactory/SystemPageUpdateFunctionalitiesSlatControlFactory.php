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
   * The obfuscator for function IDs.
   *
   * @var Obfuscator
   */
  private $funIdObfuscator;

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
    $table_column = new TextTableColumn('Module', 'mdl_name');
    $this->addSlatJoint('mdl_name', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with functionality ID.
    $table_column = new NumericTableColumn('ID', 'fun_id');
    $this->addSlatJoint('fun_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of functionality.
    $table_column = new TextTableColumn('Functionality', 'fun_name');
    $this->addSlatJoint('fun_name', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('fun_checked', $table_column);

    $this->funIdObfuscator = Abc::getObfuscator('fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($louverControl, $data)
  {
    /** @var SlatControl $row */
    $row = $louverControl->addFormControl(new SlatControl($data['fun_id']));
    $row->setObfuscator($this->funIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'fun_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'fun_name');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'fun_checked');
    $control->setValue($data['fun_checked']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
