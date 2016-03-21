<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [select](http://www.w3schools.com/tags/tag_select.asp).
 */
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The key in $myOptions holding the disabled flag for the options in this select box.
   *
   * @var string
   */
  protected $myDisabledKey;

  /**
   * If set the first option in the select box with be an option with an empty label with value $myOptionEmpty.
   *
   * @var string The value for the empty option.
   */
  protected $myEmptyOption;

  /**
   * The key in $myOptions holding the HTML ID for the options in this select box.
   *
   * @var string
   */
  protected $myIdKey;

  /**
   * The key in $myOptions holding the keys for the options in this select box.
   *
   * @var string
   */
  protected $myKeyKey;

  /**
   * The key in $myOptions holding the labels for the options in this select box.
   *
   * @var string
   */
  protected $myLabelKey;

  /**
   * The options of this select box.
   *
   * @var array[]
   */
  protected $myOptions;

  /**
   * The obfuscator for the names of the options.
   *
   * @var Obfuscator
   */
  protected $myOptionsObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $this->myAttributes['name'] = $this->mySubmitName;

    $html = $this->myPrefix;
    $html .= Html::generateTag('select', $this->myAttributes);

    // Add an empty option, if necessary.
    if (isset($this->myEmptyOption))
    {
      $html .= '<option';
      $html .= Html::generateAttribute('value', $this->myEmptyOption);
      $html .= '> </option>';
    }

    if (is_array($this->myOptions))
    {
      $option_attributes = ['value'    => '',
                            'selected' => false,
                            'disabled' => false,
                            'id'       => null];

      foreach ($this->myOptions as $option)
      {
        // Get the (database) key of the option.
        $key = (string)$option[$this->myKeyKey];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode($key) : $key;

        $option_attributes['value']    = $code;
        $option_attributes['selected'] = ((string)$this->myValue===$key);
        $option_attributes['disabled'] = (isset($this->myDisabledKey) && !empty($option[$this->myDisabledKey]));
        $option_attributes['id']       = (isset($this->myIdKey) && isset($option[$this->myIdKey])) ? $option[$this->myIdKey] : null;

        $html .= Html::generateElement('option', $option_attributes, $option[$this->myLabelKey]);
      }
    }

    $html .= '</select>';
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the options of this select box as set by {@link setOptions}.
   *
   * @return array[]
   */
  public function getOptions()
  {
    return $this->myOptions;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an option with empty label as first option to this select box.
   *
   * @param string $theEmptyOption The value for the empty option. This value will not be obfuscated.
   */
  public function setEmptyOption($theEmptyOption = ' ')
  {
    $this->myEmptyOption = $theEmptyOption;
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]     $theOptions      The options of this select box.
   * @param string      $theKeyKey       The key holding the keys of the options.
   * @param string      $theLabelKey     The key holding the labels for the options.
   * @param string|null $theDisabledKey  The key holding the disabled flag. Any none empty value results that the
   *                                     option is disables.
   * @param string|null $theIdKey        The key holding the HTML ID attribute of the options.
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
   * Sets the obfuscator for the names (most likely the names are databases IDs) of the radio buttons.
   *
   * @param Obfuscator $theObfuscator The obfuscator for the radio buttons.
   */
  public function setOptionsObfuscator($theObfuscator)
  {
    $this->myOptionsObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    // Normalize default value as a string.
    $value = (string)$this->myValue;

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted = (string)$theSubmittedValue[$submit_name];

      if (isset($this->myEmptyOption) && $submitted===(string)$this->myEmptyOption)
      {

        $this->myValue                    = null;
        $theWhiteListValue[$this->myName] = null;
        if ($value!==(string)$this->myEmptyOption)
        {
          $theChangedInputs[$this->myName] = $this;
        }
      }
      else
      {
        if (is_array($this->myOptions))
        {
          foreach ($this->myOptions as $option)
          {
            // Get the key of the option.
            $key = (string)$option[$this->myKeyKey];

            // If an obfuscator is installed compute the obfuscated code of the (database) ID.
            $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode($key) : $key;

            if ($submitted===(string)$code)
            {
              // If the original value differs from the submitted value then the form control has been changed.
              if ($value!==$key)
              {
                $theChangedInputs[$this->myName] = $this;
              }

              // Set the white listed value.
              $this->myValue                    = $key;
              $theWhiteListValue[$this->myName] = $key;

              // Leave the loop.
              break;
            }
          }
        }
      }
    }
    else
    {
      // No value has been submitted.
      $this->myValue                    = null;
      $theWhiteListValue[$this->myName] = null;
      if ($value!==(string)$this->myEmptyOption)
      {
        $theChangedInputs[$this->myName] = $this;
      }
    }

    if (!array_key_exists($this->myName, $theWhiteListValue))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      /** @todo validate the default value is a white list value. */
      $theWhiteListValue[$this->myName] = $this->myValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
