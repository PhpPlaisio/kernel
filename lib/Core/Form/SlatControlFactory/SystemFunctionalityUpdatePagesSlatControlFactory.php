<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Html\TableColumn\NumericTableColumn;
use SetBased\Html\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemFunctionalityUpdatePagesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with page ID.
    $table_column = new NumericTableColumn('ID', 'pag_id');
    $this->addSlatJoint('pag_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of class.
    $table_column = new TextTableColumn('Name', 'pag_class');
    $this->addSlatJoint('pag_class', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('pag_enabled', $table_column);

    $this->myPagIdObfuscator = abc::getObfuscator('pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($theLouverControl, $theData)
  {
    /** @var SlatControl $row */
    $row = $theLouverControl->addFormControl(new SlatControl($theData['pag_id']));
    $row->setObfuscator($this->myPagIdObfuscator);

    $control = $this->createFormControl($row, 'pag_id');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'pag_class');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'pag_enabled');
    $control->setValue($theData['pag_enabled']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
