<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\SubmitControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a input:submit form control.
 */
class SubmitSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $headerText The header text of this table column.
   */
  public function __construct($headerText)
  {
    $this->dataType   = 'control-reset';
    $this->headerText = $headerText;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a reset form control.
   *
   * @param string $name The local name of the submit form control.
   *
   * @return SubmitControl
   */
  public function createControl($name)
  {
    return new SubmitControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    return '<td></td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
