<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\RadiosControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a radios form controls.
 */
class RadiosSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->dataType   = 'control-radios';
    $this->headerText = $theHeaderText;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a radios form control.
   *
   * @param string $theName The local name of the radios form control.
   *
   * @return RadiosControl
   */
  public function createControl($theName)
  {
    return new RadiosControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
