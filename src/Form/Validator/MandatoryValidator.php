<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validates if a form control has a value. Can be applied on any form control object.
 */
class MandatoryValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the form control has a value.
   *
   * Note:
   * * Empty values are considered invalid.
   * * If the form control is a complex form control all child form control must have a value.
   *
   * @param Control $control The form control.
   *
   * @return bool
   */
  public function validate($control)
  {
    $value = $control->getSubmittedValue();

    if ($value==='' || $value===null || $value===false)
    {
      return false;
    }

    if (is_array($value))
    {
      return $this->validateArray($value);
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates recursively if one of the leaves of @a $array has a non-empty value.
   *
   * @param array $array
   *
   * @return bool
   */
  private function validateArray($array)
  {
    foreach ($array as $element)
    {
      if (is_scalar($element))
      {
        if ($element!==null && $element!==false && $element!=='')
        {
          return true;
        }
      }
      else
      {
        $tmp = $this->validateArray($element);
        if ($tmp===true)
        {
          return true;
        }
      }
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
