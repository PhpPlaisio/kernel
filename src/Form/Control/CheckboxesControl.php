<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 *
 *
 * @todo Implement disabled hard (can not be changed via javascript) and disabled sort (can be changed via javascript).
 */
class CheckboxesControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The key in $myOptions holding the checked flag for the checkboxes.
   *
   * @var string
   */
  protected $myCheckedKey;

  /**
   * The key in $myOptions holding the disabled flag for the checkboxes.
   *
   * @var string|null
   */
  protected $myDisabledKey;

  /**
   * The key in $myOptions holding the HTML ID attribute of the checkboxes.   *
   *
   * @var string|null
   */
  protected $myIdKey;

  /**
   * The key in $myOptions holding the keys for the checkboxes.
   *
   * @var string
   */
  protected $myKeyKey;

  /**
   * The key in $myOptions holding the labels for the checkboxes.
   *
   * @var string
   */
  protected $myLabelKey;

  /**
   * The HTML snippet appended after each label for the checkboxes.
   *
   * @var string
   */
  protected $myLabelPostfix = '';

  /**
   * The HTML snippet inserted before each label for the checkboxes.
   *
   * @var string
   */
  protected $myLabelPrefix = '';

  /**
   * The options of this select box.
   *
   * @var array[]
   */
  protected $myOptions;

  /**
   * The obfuscator for the names of the checkboxes.
   *
   * @var Obfuscator
   */
  protected $myOptionsObfuscator;

  /**
   * The value of the checked radio button.
   *
   * @var string
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $html = $this->myPrefix;
    $html .= Html::generateTag('span', $this->myAttributes);

    if (is_array($this->myOptions))
    {
      $input_attributes = ['type'     => 'checkbox',
                           'name'     => '',
                           'id'       => '',
                           'checked'  => false,
                           'disabled' => false];
      $label_attributes = ['for' => &$input_attributes['id']];

      foreach ($this->myOptions as $option)
      {
        $code = ($this->myOptionsObfuscator) ?
          $this->myOptionsObfuscator->encode($option[$this->myKeyKey]) : $option[$this->myKeyKey];

        $input_attributes['name']     = ($this->mySubmitName!=='') ? $this->mySubmitName.'['.$code.']' : $code;
        $input_attributes['id']       = (isset($this->myIdKey) && isset($option[$this->myIdKey])) ? $option[$this->myIdKey] : Html::getAutoId();
        $input_attributes['checked']  = (isset($this->myCheckedKey) && !empty($option[$this->myCheckedKey]));
        $input_attributes['disabled'] = (isset($this->myDisabledKey) && !empty($option[$this->myDisabledKey]));

        $html .= Html::generateVoidElement('input', $input_attributes);

        $html .= $this->myLabelPrefix;
        $html .= Html::generateElement('label', $label_attributes, $option[$this->myLabelKey]);
        $html .= $this->myLabelPostfix;
      }
    }

    $html .= '</span>';
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of checked checkboxes the values with the name of this form control as key.
   *
   * @param array $theValues The values.
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

    foreach ($this->myOptions as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->myKeyKey];

      // Get the original value (i.e. the option is checked or not).
      $tmp[$key] = (!empty($option[$this->myCheckedKey]));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control according to @a $theValues.
   *
   * @param array $theValues
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
      foreach ($this->myOptions as $id => $option)
      {
        if (array_key_exists($option[$this->myKeyKey], $values))
        {
          $this->myOptions[$id][$this->myCheckedKey] = !empty($values[$option[$this->myKeyKey]]);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label prefix, e.g. the HTML code that is inserted before the HTML code of each label of the checkboxes.
   *
   * @param string $theHtmlSnippet The label prefix.
   */
  public function setLabelPostfix($theHtmlSnippet)
  {
    $this->myLabelPostfix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label postfix., e.g. the HTML code that is appended after the HTML code of each label of the checkboxes.
   *
   * @param string $theHtmlSnippet The label postfix.
   */
  public function setLabelPrefix($theHtmlSnippet)
  {
    $this->myLabelPrefix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for the checkboxes box.
   *
   * @param array[]     $theOptions      An array of arrays with the options.
   * @param string      $theKeyKey       The key holding the keys of the checkboxes.
   * @param string      $theLabelKey     The key holding the labels for the checkboxes.
   * @param string|null $theCheckedKey   The key holding the checked flag. Any none empty value results that the
   *                                     checkbox is checked.
   * @param string|null $theDisabledKey  The key holding the disabled flag. Any none empty value results that the
   *                                     checkbox is disabled.
   * @param string|null $theIdKey        The key holding the HTML ID attribute of the checkboxes.
   */
  public function setOptions(&$theOptions,
                             $theKeyKey,
                             $theLabelKey,
                             $theCheckedKey = 'abc_map_checked',
                             $theDisabledKey = null,
                             $theIdKey = null
  )
  {
    $this->myOptions     = $theOptions;
    $this->myKeyKey      = $theKeyKey;
    $this->myLabelKey    = $theLabelKey;
    $this->myCheckedKey  = $theCheckedKey;
    $this->myDisabledKey = $theDisabledKey;
    $this->myIdKey       = $theIdKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names of the checkboxes. This method should be used when the names of the checkboxes
   * are database IDs.
   *
   * @param Obfuscator $theObfuscator The obfuscator for the checkbox names.
   */
  public function setOptionsObfuscator($theObfuscator)
  {
    $this->myOptionsObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the checkboxes, a non-empty value will check a checkbox.
   *
   * @param array $theValues The values.
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

    foreach ($this->myOptions as $id => $option)
    {
      $this->myOptions[$id][$this->myCheckedKey] = !empty($values[$option[$this->myKeyKey]]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    foreach ($this->myOptions as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->myKeyKey];

      // If an obfuscator is installed compute the obfuscated code of the (database) ID.
      $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode($key) : $key;

      // Get the original value (i.e. the option is checked or not).
      $value = (isset($option[$this->myCheckedKey])) ? $option[$this->myCheckedKey] : false;

      if ($submit_name!=='')
      {
        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$submit_name][$code])) ? $theSubmittedValue[$submit_name][$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$this->myName][$key] = $this;

        // Set the white listed value.
        $theWhiteListValue[$this->myName][$key] = !empty($submitted);

      }
      else
      {
        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$code])) ? $theSubmittedValue[$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$key] = $this;

        // Set the white listed value.
        $theWhiteListValue[$key] = !empty($submitted);
      }

      // Set the submitted value to be used method getSubmittedValue.
      $this->myValue[$key] = !empty($submitted);

      $this->myOptions[$i][$this->myCheckedKey] = !empty($submitted);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function validateBase(&$theInvalidFormControls)
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate($this);
      if ($valid!==true)
      {
        $theInvalidFormControls[$this->myName] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
