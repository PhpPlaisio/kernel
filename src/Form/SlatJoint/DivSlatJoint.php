<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\DivControl;


//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a div element.
 */
class DivSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->myDataType   = 'control-div';
    $this->myHeaderText = $theHeaderText;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a div form control.
   *
   * @param string $theName The local name of the div form control.
   *
   * @return DivControl
   */
  public function createControl($theName)
  {
    return new DivControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
