<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\SpanControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a span element.
 */
class SpanSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->dataType   = 'control-span';
    $this->headerText = $theHeaderText;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a span form control.
   *
   * @param string $theName The local name of the span form control.
   *
   * @return SpanControl
   */
  public function createControl($theName)
  {
    return new SpanControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
