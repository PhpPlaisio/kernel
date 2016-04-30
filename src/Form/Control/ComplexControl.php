<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Exception\LogicException;

/**
 * Class for complex form controls. A complex form control consists of one of more form controls.
 */
class ComplexControl extends Control implements CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted values.
   *
   * @var Cleaner
   */
  protected $cleaner;

  /**
   * The child form controls of this form control.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $controls = [];

  /**
   * The child form controls of this form control with invalid submitted values.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $invalidControls;

  /**
   * The value of this form control, i.e. a nested array of the values of the child form controls.
   *
   * @var mixed
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to this complex form control.
   *
   * @param Control $control The from control added.
   *
   * @return Control The added form control.
   */
  public function addFormControl($control)
  {
    $this->controls[] = $control;

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function findFormControlByName($name)
  {
    // Name must be string. Convert name to the string.
    $name = (string)$name;

    foreach ($this->controls as $control)
    {
      if ($control->name===$name) return $control;

      if ($control instanceof ComplexControl)
      {
        $tmp = $control->findFormControlByName($name);
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function findFormControlByPath($path)
  {
    if ($path===null || $path===false || $path==='' || $path==='/')
    {
      return null;
    }

    // $path must start with a leading slash.
    if (substr($path, 0, 1)!='/')
    {
      return null;
    }

    // Remove leading slash from the path.
    $relative_path = substr($path, 1);

    foreach ($this->controls as $control)
    {
      $parts = preg_split('/\/+/', $relative_path);

      if ($control->name==$parts[0])
      {
        if (count($parts)==1)
        {
          return $control;
        }
        else
        {
          if ($control instanceof ComplexControl)
          {
            array_shift($parts);
            $tmp = $control->findFormControlByPath('/'.implode('/', $parts));
            if ($tmp) return $tmp;
          }
        }
      }
      elseif ($control->name==='' && ($control instanceof ComplexControl))
      {
        $tmp = $control->findFormControlByPath($path);
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $ret = $this->prefix;
    foreach ($this->controls as $control)
    {
      $ret .= $control->generate();
    }
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array of all error messages of the child form controls of this complex form controls.
   *
   * @param bool $recursive If set error messages of complex child controls of this complex form controls are fetched
   *                        also.
   *
   * @return array|null
   */
  public function getErrorMessages($recursive = false)
  {
    $ret = [];
    if ($recursive)
    {
      foreach ($this->controls as $control)
      {
        $tmp = $control->getErrorMessages(true);
        if (is_array($tmp))
        {
          $ret = array_merge($ret, $tmp);
        }
      }
    }

    if (isset($this->errorMessages))
    {
      $ret = array_merge($ret, $this->errorMessages);
    }

    if (empty($ret)) $ret = null;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getFormControlByName($name)
  {
    $control = $this->findFormControlByName($name);
    if ($control===null)
    {
      throw new LogicException("Form control with name '%s' does not exists.", $name);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getFormControlByPath($path)
  {
    $control = $this->findFormControlByPath($path);
    if ($control===null)
    {
      throw new LogicException("Form control with path '%s' does not exists.", $path);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getSetValuesBase(&$values)
  {
    if ($this->name==='')
    {
      $tmp = &$values;
    }
    else
    {
      $values[$this->name] = [];
      $tmp                 = &$values[$this->name];
    }

    foreach ($this->controls as $control)
    {
      $control->getSetValuesBase($tmp);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @returns array
   */
  public function getSubmittedValue()
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the submitted values of this complex form control and this child form control are valid.
   * Otherwise, returns false.
   *
   * @return bool
   */
  public function isValid()
  {
    return (empty($this->invalidControls) && empty($this->errorMessages));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if ($this->name==='')
    {
      $tmp1 = &$submittedValue;
      $tmp2 = &$whiteListValue;
      $tmp3 = &$changedInputs;
    }
    else
    {
      $tmp1 = &$submittedValue[$submit_name];
      $tmp2 = &$whiteListValue[$this->name];
      $tmp3 = &$changedInputs[$this->name];
    }

    foreach ($this->controls as $control)
    {
      if ($this->cleaner) $tmp1 = $this->cleaner->clean($tmp1);
      $control->loadSubmittedValuesBase($tmp1, $tmp2, $tmp3);
    }

    if ($this->name!=='')
    {
      if (empty($whiteListValue[$this->name])) unset($whiteListValue[$this->name]);
      if (empty($changedInputs[$this->name])) unset($changedInputs[$this->name]);
    }

    // Set the submitted values.
    $this->value = $tmp2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are not affected.
   *
   * @param mixed $values The values as a nested array.
   */
  public function mergeValuesBase($values)
  {
    if ($this->name==='')
    {
      // Nothing to do.
      ;
    }
    elseif (isset($values[$this->name]))
    {
      $values = &$values[$this->name];
    }
    else
    {
      $values = null;
    }

    if ($values!==null)
    {
      foreach ($this->controls as $control)
      {
        $control->mergeValuesBase($values);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form complex control for HTML code generation or loading submitted values.
   *
   * @param string $parentSubmitName The submit name of the parent control.
   */
  public function prepare($parentSubmitName)
  {
    parent::prepare($parentSubmitName);

    foreach ($this->controls as $control)
    {
      $control->prepare($this->submitName);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner $cleaner The cleaner.
   */
  public function setCleaner($cleaner)
  {
    $this->cleaner = $cleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are set to null.
   *
   * @param mixed $values The values as a nested array.
   */
  public function setValue($values)
  {
    foreach ($this->controls as $control)
    {
      $control->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are set to null.
   *
   * @param mixed $values The values as a nested array.
   */
  public function setValuesBase($values)
  {
    if ($this->name==='')
    {
      // Nothing to do.
      ;
    }
    elseif (isset($values[$this->name]))
    {
      $values = &$values[$this->name];
    }
    else
    {
      $values = null;
    }

    foreach ($this->controls as $control)
    {
      $control->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes a validators on the child form controls of this form complex control. If  and only if all child form
   * controls are valid the validators of this complex control are executed.
   *
   * @param array $invalidFormControls A nested array of invalid form controls.
   *
   * @return bool True if and only if all form controls are valid.
   */
  public function validateBase(&$invalidFormControls)
  {
    $valid = true;

    // First, validate all child form controls.
    foreach ($this->controls as $control)
    {
      if (!$control->validateBase($invalidFormControls))
      {
        $this->invalidControls[] = $control;
        $valid                   = false;
      }
    }

    if ($valid)
    {
      // All the child form controls are valid. Validate this complex form control.
      foreach ($this->validators as $validator)
      {
        $valid = $validator->validate($this);
        if ($valid!==true)
        {
          $invalidFormControls[] = $this;
          break;
        }
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
