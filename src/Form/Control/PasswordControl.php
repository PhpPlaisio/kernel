<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Abc\Helper\Html;

//--------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:password](http://www.w3schools.com/tags/tag_input.asp).
 */
class PasswordControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    // By default whitespace is trimmed from password form controls.
    $this->myCleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type']  = 'password';
    $this->attributes['name']  = $this->mySubmitName;
    $this->attributes['value'] = $this->myValue;

    if (isset($this->attributes['maxlength']))
    {
      if (isset($this->attributes['size']))
      {
        $this->attributes['size'] = min($this->attributes['size'], $this->attributes['maxlength']);
      }
      else
      {
        $this->attributes['size'] = $this->attributes['maxlength'];
      }
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
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    // Get the submitted value and cleaned (if required).
    if ($this->myCleaner)
    {
      $new_value = $this->myCleaner->clean($theSubmittedValue[$submit_name]);
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }

    if ((string)$this->myValue!==(string)$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myValue                   = $new_value;
    }

    // The user can enter any text in a input:password box. So, any value is white listed.
    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
