<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validator for http URLs.
 */
class HttpValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is a valid http URL.
   *
   * Note:
   * * Empty values are considered valid.
   * * This validator will test if the URL actually exists.
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

    // Objects and arrays are not a valid http URL.
    if (!is_scalar($value))
    {
      return false;
    }

    // Filter valid URL from the value.
    $url = filter_var($value, FILTER_VALIDATE_URL);

    // If the actual value and the filtered value are not equal the value is not a valid url.
    if ($url!==$value)
    {
      return false;
    }

    // filter_var allows not to specify the HTTP protocol. Test the URL starts with http (or https).
    if (substr($url, 0, 4)!=='http')
    {
      return false;
    }

    // Test that the page actually exits. We consider all HTTP 200-399 responses are valid.
    $headers = get_headers($url);
    $ok      = (is_array($headers) && preg_match('/^HTTP\\/\\d+\\.\\d+\\s+[23]\\d\\d\\s*.*$/', $headers[0]));

    return $ok;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
