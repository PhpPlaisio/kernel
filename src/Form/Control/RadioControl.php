<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:radio](http://www.w3schools.com/tags/tag_input.asp).
 *
 * @todo  Add attribute for label.
 */
class RadioControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type'] = 'radio';
    $this->attributes['name'] = $this->submitName;

    // A radio button is checked if and only if its value equals to the value of attribute value.
    if (isset($this->attributes['value']) && ((string)$this->value===(string)$this->attributes['value']))
    {
      $this->attributes['checked'] = true;
    }
    else
    {
      unset($this->attributes['checked']);
    }

    $ret = $this->prefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [value](http://www.w3schools.com/tags/att_input_value.asp).
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrValue($value)
  {
    $this->attributes['value'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;
    $new_value   = (isset($submittedValue[$submit_name])) ? (string)$submittedValue[$submit_name] : null;

    if (isset($this->attributes['value']) && $new_value===(string)$this->attributes['value'])
    {
      if (empty($this->attributes['checked']))
      {
        $changedInputs[$this->name] = $this;
      }
      $this->attributes['checked'] = true;
      $whiteListValue[$this->name] = $this->attributes['value'];
      $this->value                 = $this->attributes['value'];
    }
    else
    {
      if (!empty($this->attributes['checked']))
      {
        $changedInputs[$this->name] = $this;
      }
      $this->attributes['checked'] = false;
      $this->value                 = null;

      // If the white listed value is not set by a radio button with the same name as this radio button, set the white
      // listed value of this radio button (and other radio buttons with the same name) to null. If another radio button
      // with the same name is checked the white listed value will be overwritten.
      if (!isset($whiteListValue[$this->name]))
      {
        $whiteListValue[$this->name] = null;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
