<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validator for integer values.
 */
class IntegerValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The upper bound of the range of valid (integer) values.
   *
   * @var int|null
   */
  private $maxValue;

  /**
   * The lower bound of the range of valid (integer) values.
   *
   * @var int
   */
  private $minValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int|null $minValue The minimum required value.
   * @param int      $maxValue The maximum required value.
   */
  public function __construct($minValue = null, $maxValue = PHP_INT_MAX)
  {
    $this->minValue = (isset($minValue)) ? $minValue : -PHP_INT_MAX;
    $this->maxValue = $maxValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is an integer and with the specified range. Otherwise returns false.
   *
   * Note:
   * * Empty values are considered valid.
   *
   * @param Control $control The form control.
   *
   * @return bool
   */
  public function validate($control)
  {
    $options = ['options' => ['min_range' => $this->minValue,
                              'max_range' => $this->maxValue]];

    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null || $value===false)
    {
      return true;
    }

    // Objects and arrays are not an integer.
    if (!is_scalar($value))
    {
      return false;
    }

    // Filter valid integer values with valid range.
    $integer = filter_var($value, FILTER_VALIDATE_INT, $options);

    // If the actual value and the filtered value are not equal the value is not an integer.
    if ((string)$integer!==(string)$value)
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
