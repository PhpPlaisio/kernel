<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Table\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
class TableColumnSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param TableColumn $theTableColumn
   */
  public function __construct($theTableColumn)
  {
    $this->myDataType    = $theTableColumn->getDataType();
    $this->myTableColumn = $theTableColumn;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a button form control.
   *
   * @param string $theName The local name of the button form control.
   *
   * @return TableColumnControl
   */
  public function createCell($theName)
  {
    $control = new TableColumnControl($theName);
    $control->setTableColumn($this->myTableColumn);

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getColSpan()
  {
    return $this->myTableColumn->getColSpan();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlColumn()
  {
    return $this->myTableColumn->getHtmlColumn();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlColumnFilter()
  {
    return $this->myTableColumn->getHtmlColumnFilter();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlColumnHeader()
  {
    return $this->myTableColumn->getHtmlColumnHeader();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
