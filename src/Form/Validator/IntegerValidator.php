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
   * @var int
   */
  private $myMaxValue;

  /**
   * The lower bound of the range of valid (integer) values.
   *
   * @var int
   */
  private $myMinValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theMinValue The minimum required value.
   * @param int $theMaxValue The maximum required value.
   */
  public function __construct($theMinValue = null, $theMaxValue = PHP_INT_MAX)
  {
    $this->myMinValue = (isset($theMinValue)) ? $theMinValue : -PHP_INT_MAX;
    $this->myMaxValue = $theMaxValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is an integer and with the specified range. Otherwise returns false.
   *
   * Note:
   * * Empty values are considered valid.
   *
   * @param Control $theFormControl The form control.
   *
   * @return bool
   */
  public function validate($theFormControl)
  {
    $options = ['options' => ['min_range' => $this->myMinValue,
                              'max_range' => $this->myMaxValue]];

    $value = $theFormControl->getSubmittedValue();

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
