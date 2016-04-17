<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:text](http://www.w3schools.com/tags/tag_input.asp).
 */
class TextControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    // By default whitespace is pruned from text form controls.
    $this->myCleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $this->attributes['type'] = 'text';
    $this->attributes['name'] = $this->mySubmitName;

    if ($this->myFormatter) $this->attributes['value'] = $this->myFormatter->format($this->myValue);
    else                    $this->attributes['value'] = $this->myValue;

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
    $new_value = (isset($theSubmittedValue[$submit_name])) ? $theSubmittedValue[$submit_name] : null;
    if ($this->myCleaner) $new_value = $this->myCleaner->clean($new_value);

    if ((string)$this->myValue!==(string)$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myValue                   = $new_value;
    }

    // The user can enter any text in a input:text box. So, any value is white listed.
    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
