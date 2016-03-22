<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\InvisibleSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemFunctionalityUpdateRolesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for role IDs.
   * 
   * @var Obfuscator
   */
  private $myRolIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with page ID.
    $slat_joint = new InvisibleSlatJoint();
    $this->addSlatJoint('cmp_id', $slat_joint);

    $table_column = new NumericTableColumn('ID', 'cmp_id');
    $this->addSlatJoint('cmp_id_column', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of class.
    $table_column = new TextTableColumn('Company', 'cmp_abbr');
    $this->addSlatJoint('cmp_abbr', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with page ID.
    $table_column = new NumericTableColumn('ID', 'rol_id');
    $this->addSlatJoint('rol_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of class.
    $table_column = new TextTableColumn('Role', 'rol_name');
    $this->addSlatJoint('rol_name', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $slat_joint = new CheckboxSlatJoint('Grant');
    $this->addSlatJoint('rol_enabled', $slat_joint);

    $this->myRolIdObfuscator = Abc::getObfuscator('rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($theLouverControl, $theData)
  {
    /** @var SlatControl $row */
    $row = $theLouverControl->addFormControl(new SlatControl($theData['rol_id']));
    $row->setObfuscator($this->myRolIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_id');
    $control->setValue($theData['cmp_id']);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_id_column');
    $control->setValue($theData);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_abbr');
    $control->setValue($theData);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'rol_id');
    $control->setValue($theData);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'rol_name');
    $control->setValue($theData);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'rol_enabled');
    $control->setValue($theData['rol_enabled']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
