<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class RadiosControl
 *
 * @package SetBased\Form\Form\Control
 */
class RadiosControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The key in $myOptions holding the disabled flag for the radio buttons.
   */
  protected $myDisabledKey;

  /**
   * @var string|null The key in $myOptions holding the HTML ID attribute of the radios.
   */
  protected $myIdKey;

  /**
   * @var string The key in $myOptions holding the keys for the radio buttons.
   */
  protected $myKeyKey;

  /**
   * @var string The key in $myOptions holding the labels for the radio buttons.
   */
  protected $myLabelKey;

  /**
   * @var string The HTML snippet appended after each label for the radio buttons.
   */
  protected $myLabelPostfix = '';

  /**
   * @var string The HTML snippet inserted before each label for the radio buttons.
   */
  protected $myLabelPrefix = '';

  /**
   * @var array[] The data for the radio buttons.
   */
  protected $myOptions;

  /**
   * @var Obfuscator The obfuscator for the names of the radio buttons.
   */
  protected $myOptionsObfuscator;

  /**
   * @var string The value of the checked radio button.
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $html = $this->myPrefix;
    $html .= Html::generateTag('span', $this->attributes);

    if (is_array($this->myOptions))
    {
      $input_attributes = ['type'     => 'radio',
                           'name'     => $this->mySubmitName,
                           'id'       => '',
                           'value'    => '',
                           'checked'  => false,
                           'disabled' => false];
      // Note: we use a reference to unsure that the for attribute of the label and the id attribute of the radio
      // button match.
      $label_attributes = ['for' => &$input_attributes['id']];

      foreach ($this->myOptions as $option)
      {
        $key   = (string)$option[$this->myKeyKey];
        $value = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode($key) : $key;

        $input_attributes['id']       = (isset($this->myIdKey) && isset($option[$this->myIdKey])) ? $option[$this->myIdKey] : Html::getAutoId();
        $input_attributes['value']    = $value;
        $input_attributes['checked']  = ((string)$this->myValue===(string)$key);
        $input_attributes['disabled'] = ($this->myDisabledKey && !empty($option[$this->myDisabledKey]));

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
   * Adds the value of a check radio button the values with the name of this form control as key.
   *
   * @param array $theValues The values.
   */
  public function getSetValuesBase(&$theValues)
  {
    $theValues[$this->myName] = $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of the check radio button.
   *
   * @returns string
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of the checked radio button.
   *
   * @param array $theValues
   */
  public function mergeValuesBase($theValues)
  {
    if (array_key_exists($this->myName, $theValues))
    {
      $this->setValuesBase($theValues);
    }
  }

//--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label postfix., e.g. the HTML code that is appended after the HTML code of each label of the checkboxes.
   *
   * @param string $theHtmlSnippet The label postfix.
   */
  public function setLabelPostfix($theHtmlSnippet)
  {
    $this->myLabelPostfix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label prefix, e.g. the HTML code that is inserted before the HTML code of each label of the checkboxes.
   *
   * @param string $theHtmlSnippet The label prefix.
   */
  public function setLabelPrefix($theHtmlSnippet)
  {
    $this->myLabelPrefix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]     $theOptions      An array of arrays with the options.
   * @param string      $theKeyKey       The key holding the keys of the radio buttons.
   * @param string      $theLabelKey     The key holding the labels for the radio buttons..
   * @param string|null $theDisabledKey  The key holding the disabled flag. Any none empty value results that the
   *                                     radio button is disabled.
   * @param string|null $theIdKey        The key holding the HTML ID attribute of the radios.
   */
  public function setOptions(&$theOptions, $theKeyKey, $theLabelKey, $theDisabledKey = null, $theIdKey = null)
  {
    $this->myOptions     = $theOptions;
    $this->myKeyKey      = $theKeyKey;
    $this->myLabelKey    = $theLabelKey;
    $this->myDisabledKey = $theDisabledKey;
    $this->myIdKey       = $theIdKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the values of the radio buttons. This method should be used when the values of the radio
   * buttons are database IDs.
   *
   * @param Obfuscator $theObfuscator The obfuscator for the radio buttons values.
   */
  public function setOptionsObfuscator($theObfuscator)
  {
    $this->myOptionsObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string $theValue The new value for the form control.
   */
  public function setValue($theValue)
  {
    $this->myValue = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function setValuesBase($theValues)
  {
    $this->myValue = (isset($theValues[$this->myName])) ? $theValues[$this->myName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted_value = (string)$theSubmittedValue[$submit_name];

      foreach ($this->myOptions as $option)
      {
        // Get the (database) ID of the option.
        $key = (string)$option[$this->myKeyKey];

        // If an obfuscator is installed compute the obfuscated code of the radio button name.
        $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode($key) : $key;

        if ($submitted_value===(string)$code)
        {
          // If the original value differs from the submitted value then the form control has been changed.
          if ((string)$this->myValue!==$key)
          {
            $theChangedInputs[$this->myName] = $this;
          }

          // Set the white listed value.
          $theWhiteListValue[$this->myName] = $key;
          $this->myValue                    = $key;

          // Leave the loop.
          break;
        }
      }
    }
    else
    {
      // No radio button has been checked.
      $theWhiteListValue[$this->myName] = null;
      $this->myValue                    = null;
    }

    if (!array_key_exists($this->myName, $theWhiteListValue))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      /** @todo validate the default value is a white list value */
      $theWhiteListValue[$this->myName] = $this->myValue;
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
        $theInvalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
