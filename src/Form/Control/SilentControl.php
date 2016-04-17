<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp), however
 */
class SilentControl extends SimpleControl
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
    $this->attributes['name'] = $this->mySubmitName;

    if ($this->myFormatter) $this->attributes['value'] = $this->myFormatter->format($this->myValue);
    else                    $this->attributes['value'] = $this->myValue;

    $ret = $this->myPrefix;
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A silent control must never be shown in a table.
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
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    // Get the submitted value and clean it (if required).
    if (isset($theSubmittedValue[$submit_name]))
    {
      if ($this->myCleaner)
      {
        $new_value = $this->myCleaner->clean($theSubmittedValue[$submit_name]);
      }
      else
      {
        $new_value = $theSubmittedValue[$submit_name];
      }
    }
    else
    {
      $new_value = null;
    }

    // Normalize old (original) value and new (submitted) value.
    $old_value = (string)$this->myValue;
    $new_value = (string)$new_value;

    if ($old_value!==$new_value)
    {
      $this->myValue = $new_value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
