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
    $this->attributes['name'] = $this->mySubmitName;

    // A radio button is checked if and only if its value equals to the value of attribute value.
    if (isset($this->attributes['value']) && ((string)$this->myValue===(string)$this->attributes['value']))
    {
      $this->attributes['checked'] = true;
    }
    else
    {
      unset($this->attributes['checked']);
    }

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [value](http://www.w3schools.com/tags/att_input_value.asp).
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrValue($theValue)
  {
    $this->attributes['value'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;
    $new_value   = (isset($theSubmittedValue[$submit_name])) ? (string)$theSubmittedValue[$submit_name] : null;

    if (isset($this->attributes['value']) && $new_value===(string)$this->attributes['value'])
    {
      if (empty($this->attributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->attributes['checked']      = true;
      $theWhiteListValue[$this->myName] = $this->attributes['value'];
      $this->myValue                    = $this->attributes['value'];
    }
    else
    {
      if (!empty($this->attributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->attributes['checked'] = false;
      $this->myValue               = null;

      // If the white listed value is not set by a radio button with the same name as this radio button, set the white
      // listed value of this radio button (and other radio buttons with the same name) to null. If another radio button
      // with the same name is checked the white listed value will be overwritten.
      if (!isset($theWhiteListValue[$this->myName]))
      {
        $theWhiteListValue[$this->myName] = null;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
