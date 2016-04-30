<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

/**
 * Validator for email addresses.
 */
class EmailValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is a valid email address. Otherwise returns false.
   *
   * Note:
   * * Empty values are considered valid.
   * * This validator will test if the domain exists.
   *
   * @param Control $control The form control.
   *
   * @return bool
   */
  public function validate($control)
  {
    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null || $value===false)
    {
      return true;
    }

    // Objects and arrays are not valid email addresses.
    if (!is_scalar($value))
    {
      return false;
    }

    // Filter valid email address from the value.
    $email = filter_var($value, FILTER_VALIDATE_EMAIL);

    // If the actual value and the filtered value are not equal the value is not a valid email address.
    if ($email!==$value)
    {
      return false;
    }

    // Test if the domain does exists.
    $domain = substr(strstr($value, '@'), 1);
    if ($domain===false || $domain==='')
    {
      return false;
    }

    // The domain must have a MX or A record.
    if (!(checkdnsrr($domain.'.', 'MX') || checkdnsrr($domain.'.', 'A')))
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
