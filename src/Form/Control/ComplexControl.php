<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Affirm\Exception\LogicException;

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
  protected $myCleaner;

  /**
   * The child form controls of this form control.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $myControls = [];

  /**
   * The child form controls of this form control with invalid submitted values.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $myInvalidControls;

  /**
   * The value of this form control, i.e. a nested array of the values of the child form controls.
   *
   * @var mixed
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to this complex form control.
   *
   * @param Control $theControl The from control added.
   *
   * @return Control The added form control.
   */
  public function addFormControl($theControl)
  {
    $this->myControls[] = $theControl;

    return $theControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function findFormControlByName($theName)
  {
    // Name must be string. Convert name to the string.
    $name = (string)$theName;

    foreach ($this->myControls as $control)
    {
      if ($control->myName===$name) return $control;

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
  public function findFormControlByPath($thePath)
  {
    if ($thePath===null || $thePath===false || $thePath==='' || $thePath==='/')
    {
      return null;
    }

    // $thePath must start with a leading slash.
    if (substr($thePath, 0, 1)!='/')
    {
      return null;
    }

    // Remove leading slash from the path.
    $path = substr($thePath, 1);

    foreach ($this->myControls as $control)
    {
      $parts = preg_split('/\/+/', $path);

      if ($control->myName==$parts[0])
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
      elseif ($control->myName==='' && ($control instanceof ComplexControl))
      {
        $tmp = $control->findFormControlByPath($thePath);
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
    $ret = $this->myPrefix;
    foreach ($this->myControls as $control)
    {
      $ret .= $control->generate();
    }
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array of all error messages of the child form controls of this complex form controls.
   *
   * @param bool $theRecursiveFlag If set error messages of complex child controls of this complex form controls are
   *                               fetched also.
   *
   * @return array|null
   */
  public function getErrorMessages($theRecursiveFlag = false)
  {
    $ret = [];
    if ($theRecursiveFlag)
    {
      foreach ($this->myControls as $control)
      {
        $tmp = $control->getErrorMessages(true);
        if (is_array($tmp))
        {
          $ret = array_merge($ret, $tmp);
        }
      }
    }

    if (isset($this->myErrorMessages))
    {
      $ret = array_merge($ret, $this->myErrorMessages);
    }

    if (empty($ret)) $ret = null;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getFormControlByName($theName)
  {
    $control = $this->findFormControlByName($theName);
    if ($control===null)
    {
      throw new LogicException("Form control with name '%s' does not exists.", $theName);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getFormControlByPath($thePath)
  {
    $control = $this->findFormControlByPath($thePath);
    if ($control===null)
    {
      throw new LogicException("Form control with path '%s' does not exists.", $thePath);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getSetValuesBase(&$theValues)
  {
    if ($this->myName==='')
    {
      $tmp = &$theValues;
    }
    else
    {
      $theValues[$this->myName] = [];
      $tmp                      = &$theValues[$this->myName];
    }

    foreach ($this->myControls as $control)
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
    return $this->myValue;
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
    return (empty($this->myInvalidControls) && empty($this->myErrorMessages));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if ($this->myName==='')
    {
      $tmp1 = &$theSubmittedValue;
      $tmp2 = &$theWhiteListValue;
      $tmp3 = &$theChangedInputs;
    }
    else
    {
      $tmp1 = &$theSubmittedValue[$submit_name];
      $tmp2 = &$theWhiteListValue[$this->myName];
      $tmp3 = &$theChangedInputs[$this->myName];
    }

    foreach ($this->myControls as $control)
    {
      if ($this->myCleaner) $tmp1 = $this->myCleaner->clean($tmp1);
      $control->loadSubmittedValuesBase($tmp1, $tmp2, $tmp3);
    }

    if ($this->myName!=='')
    {
      if (empty($theWhiteListValue[$this->myName])) unset($theWhiteListValue[$this->myName]);
      if (empty($theChangedInputs[$this->myName])) unset($theChangedInputs[$this->myName]);
    }

    // Set the submitted values.
    $this->myValue = $tmp2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are not affected.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function mergeValuesBase($theValues)
  {
    if ($this->myName==='')
    {
      $values = &$theValues;
    }
    elseif (isset($theValues[$this->myName]))
    {
      $values = &$theValues[$this->myName];
    }
    else
    {
      $values = null;
    }

    if ($values!==null)
    {
      foreach ($this->myControls as $control)
      {
        $control->mergeValuesBase($values);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form complex control for HTML code generation or loading submitted values.
   *
   * @param string $theParentSubmitName The submit name of the parent control.
   */
  public function prepare($theParentSubmitName)
  {
    parent::prepare($theParentSubmitName);

    foreach ($this->myControls as $control)
    {
      $control->prepare($this->mySubmitName);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner $theCleaner The cleaner.
   */
  public function setCleaner($theCleaner)
  {
    $this->myCleaner = $theCleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are set to null.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function setValue($theValues)
  {
    foreach ($this->myControls as $control)
    {
      $control->setValuesBase($theValues);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are set to null.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function setValuesBase($theValues)
  {
    if ($this->myName==='')
    {
      $values = &$theValues;
    }
    elseif (isset($theValues[$this->myName]))
    {
      $values = &$theValues[$this->myName];
    }
    else
    {
      $values = null;
    }

    foreach ($this->myControls as $control)
    {
      $control->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes a validators on the child form controls of this form complex control. If  and only if all child form
   * controls are valid the validators of this complex control are executed.
   *
   * @param array $theInvalidFormControls A nested array of invalid form controls.
   *
   * @return bool True if and only if all form controls are valid.
   */
  public function validateBase(&$theInvalidFormControls)
  {
    $valid = true;

    // First, validate all child form controls.
    foreach ($this->myControls as $control)
    {
      if (!$control->validateBase($theInvalidFormControls))
      {
        $this->myInvalidControls[] = $control;
        $valid                     = false;
      }
    }

    if ($valid)
    {
      // All the child form controls are valid. Validate this complex form control.
      foreach ($this->myValidators as $validator)
      {
        $valid = $validator->validate($this);
        if ($valid!==true)
        {
          $theInvalidFormControls[] = $this;
          break;
        }
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
