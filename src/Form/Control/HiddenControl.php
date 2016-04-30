<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp).
 */
class HiddenControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type'] = 'hidden';
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                    $this->attributes['value'] = $this->value;

    $ret = $this->prefix;
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A hidden control must never be shown in a table.
   *
   * @return string An empty string.
   */
  public function getHtmlTableCell()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    // Get the submitted value and clean it (if required).
    if ($this->cleaner)
    {
      $new_value = $this->cleaner->clean($submittedValue[$submit_name]);
    }
    else
    {
      $new_value = $submittedValue[$submit_name];
    }

    // Normalize old (original) value and new (submitted) value.
    $old_value = (string)$this->value;
    $new_value = (string)$new_value;

    if ($old_value!==$new_value)
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $new_value;
    }

    // Any text can be in a input:hidden box. So, any value is white listed.
    $whiteListValue[$this->name] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
