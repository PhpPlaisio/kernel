<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Table\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SpanControl
 *
 * @package SetBased\Form\Form\Control
 */
class TableColumnControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var array
   */
  protected $myRow;

  /**
   * @var TableColumn
   */
  protected $myTableColumn;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlTableCell()
  {
    return $this->myTableColumn->getHtmlCell($this->myRow);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null.
   *
   * @return null
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the table column of this form control.
   *
   * @param TableColumn $theTableColumn
   */
  public function setTableColumn($theTableColumn)
  {
    $this->myTableColumn = $theTableColumn;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the data for to be used by the table column for generating the table cell.
   *
   * @param array $theRow
   */
  public function setValue($theRow)
  {
    $this->myRow = $theRow;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase(&$theInvalidFormControls)
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
