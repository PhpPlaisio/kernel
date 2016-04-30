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
  public function __construct($name)
  {
    parent::__construct($name);

    // By default whitespace is pruned from text form controls.
    $this->cleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $this->attributes['type'] = 'text';
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                    $this->attributes['value'] = $this->value;

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

    $ret = $this->prefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    // Get the submitted value and cleaned (if required).
    $new_value = (isset($submittedValue[$submit_name])) ? $submittedValue[$submit_name] : null;
    if ($this->cleaner) $new_value = $this->cleaner->clean($new_value);

    if ((string)$this->value!==(string)$new_value)
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $new_value;
    }

    // The user can enter any text in a input:text box. So, any value is white listed.
    $whiteListValue[$this->name] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
