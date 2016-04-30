<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\ComplexControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a complex form control.
 */
class ComplexSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->dataType   = 'control-complex';
    $this->headerText = $theHeaderText;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a complex form control.
   *
   * @param string $theName The local name of the complex form control.
   *
   * @return ComplexControl
   */
  public function createControl($theName)
  {
    return new ComplexControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
