<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Interface for defining classes for cleaning submitted values and translating formatted values to machine values.
 */
interface Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans a submitted value.
   *
   * @param mixed $value The submitted value.
   *
   * @return mixed The cleaned submitted value.
   */
  public function clean($value);
}

//----------------------------------------------------------------------------------------------------------------------
