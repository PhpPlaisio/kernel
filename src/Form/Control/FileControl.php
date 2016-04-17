<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:file](http://www.w3schools.com/tags/tag_input.asp).
 */
class FileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type'] = 'file';
    $this->attributes['name'] = $this->mySubmitName;

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accept](http://www.w3schools.com/tags/att_input_accept.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrForm($theValue)
  {
    $this->attributes['accept'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Does nothing. It is not possible the set the value of a file form control.
   *
   * @param string $theValue Not used.
   */
  public function setValue($theValue)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if ($_FILES[$submit_name]['error']===0)
    {
      $theChangedInputs[$this->myName]  = $this;
      $theWhiteListValue[$this->myName] = $_FILES[$submit_name];
      $this->myValue                    = $_FILES[$submit_name];
    }
    else
    {
      $this->myValue                    = null;
      $theWhiteListValue[$this->myName] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
