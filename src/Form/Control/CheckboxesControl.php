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
   * The key in $options holding the checked flag for the checkboxes.
   *
   * @var string
   */
  protected $checkedKey;

  /**
   * The key in $options holding the disabled flag for the checkboxes.
   *
   * @var string|null
   */
  protected $disabledKey;

  /**
   * The key in $options holding the HTML ID attribute of the checkboxes.   *
   *
   * @var string|null
   */
  protected $idKey;

  /**
   * The key in $options holding the keys for the checkboxes.
   *
   * @var string
   */
  protected $keyKey;

  /**
   * The key in $options holding the labels for the checkboxes.
   *
   * @var string
   */
  protected $labelKey;

  /**
   * The HTML snippet appended after each label for the checkboxes.
   *
   * @var string
   */
  protected $labelPostfix = '';

  /**
   * The HTML snippet inserted before each label for the checkboxes.
   *
   * @var string
   */
  protected $labelPrefix = '';

  /**
   * The options of this select box.
   *
   * @var array[]
   */
  protected $options;

  /**
   * The obfuscator for the names of the checkboxes.
   *
   * @var Obfuscator
   */
  protected $optionsObfuscator;

  /**
   * The value of the checked radio button.
   *
   * @var string
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $html = $this->prefix;
    $html .= Html::generateTag('span', $this->attributes);

    if (is_array($this->options))
    {
      $input_attributes = ['type'     => 'checkbox',
                           'name'     => '',
                           'id'       => '',
                           'checked'  => false,
                           'disabled' => false];
      $label_attributes = ['for' => &$input_attributes['id']];

      foreach ($this->options as $option)
      {
        $code = ($this->optionsObfuscator) ?
          $this->optionsObfuscator->encode($option[$this->keyKey]) : $option[$this->keyKey];

        $input_attributes['name']     = ($this->submitName!=='') ? $this->submitName.'['.$code.']' : $code;
        $input_attributes['id']       = (isset($this->idKey) && isset($option[$this->idKey])) ? $option[$this->idKey] : Html::getAutoId();
        $input_attributes['checked']  = (isset($this->checkedKey) && !empty($option[$this->checkedKey]));
        $input_attributes['disabled'] = (isset($this->disabledKey) && !empty($option[$this->disabledKey]));

        $html .= Html::generateVoidElement('input', $input_attributes);

        $html .= $this->labelPrefix;
        $html .= Html::generateElement('label', $label_attributes, $option[$this->labelKey]);
        $html .= $this->labelPostfix;
      }
    }

    $html .= '</span>';
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of checked checkboxes the values with the name of this form control as key.
   *
   * @param array $values The values.
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

    foreach ($this->options as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->keyKey];

      // Get the original value (i.e. the option is checked or not).
      $tmp[$key] = (!empty($option[$this->checkedKey]));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getSubmittedValue()
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control according to @a $values.
   *
   * @param array $values
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
      foreach ($this->options as $id => $option)
      {
        if (array_key_exists($option[$this->keyKey], $values))
        {
          $this->options[$id][$this->checkedKey] = !empty($values[$option[$this->keyKey]]);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label prefix, e.g. the HTML code that is inserted before the HTML code of each label of the checkboxes.
   *
   * @param string $htmlSnippet The label prefix.
   */
  public function setLabelPostfix($htmlSnippet)
  {
    $this->labelPostfix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label postfix., e.g. the HTML code that is appended after the HTML code of each label of the checkboxes.
   *
   * @param string $htmlSnippet The label postfix.
   */
  public function setLabelPrefix($htmlSnippet)
  {
    $this->labelPrefix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for the checkboxes box.
   *
   * @param array[]     $options         An array of arrays with the options.
   * @param string      $keyKey          The key holding the keys of the checkboxes.
   * @param string      $labelKey        The key holding the labels for the checkboxes.
   * @param string|null $checkedKey      The key holding the checked flag. Any none empty value results that the
   *                                     checkbox is checked.
   * @param string|null $disabledKey     The key holding the disabled flag. Any none empty value results that the
   *                                     checkbox is disabled.
   * @param string|null $idKey           The key holding the HTML ID attribute of the checkboxes.
   */
  public function setOptions(&$options,
                             $keyKey,
                             $labelKey,
                             $checkedKey = 'abc_map_checked',
                             $disabledKey = null,
                             $idKey = null
  )
  {
    $this->options     = $options;
    $this->keyKey      = $keyKey;
    $this->labelKey    = $labelKey;
    $this->checkedKey  = $checkedKey;
    $this->disabledKey = $disabledKey;
    $this->idKey       = $idKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names of the checkboxes. This method should be used when the names of the checkboxes
   * are database IDs.
   *
   * @param Obfuscator $obfuscator The obfuscator for the checkbox names.
   */
  public function setOptionsObfuscator($obfuscator)
  {
    $this->optionsObfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the checkboxes, a non-empty value will check a checkbox.
   *
   * @param array $values The values.
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

    foreach ($this->options as $id => $option)
    {
      $this->options[$id][$this->checkedKey] = !empty($values[$option[$this->keyKey]]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    foreach ($this->options as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->keyKey];

      // If an obfuscator is installed compute the obfuscated code of the (database) ID.
      $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode($key) : $key;

      // Get the original value (i.e. the option is checked or not).
      $value = (isset($option[$this->checkedKey])) ? $option[$this->checkedKey] : false;

      if ($submit_name!=='')
      {
        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($submittedValue[$submit_name][$code])) ? $submittedValue[$submit_name][$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $changedInputs[$this->name][$key] = $this;

        // Set the white listed value.
        $whiteListValue[$this->name][$key] = !empty($submitted);
      }
      else
      {
        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($submittedValue[$code])) ? $submittedValue[$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $changedInputs[$key] = $this;

        // Set the white listed value.
        $whiteListValue[$key] = !empty($submitted);
      }

      // Set the submitted value to be used method getSubmittedValue.
      $this->value[$key] = !empty($submitted);

      $this->options[$i][$this->checkedKey] = !empty($submitted);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function validateBase(&$invalidFormControls)
  {
    $valid = true;

    foreach ($this->validators as $validator)
    {
      $valid = $validator->validate($this);
      if ($valid!==true)
      {
        $invalidFormControls[$this->name] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
