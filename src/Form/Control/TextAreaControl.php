<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\TrimWhitespaceCleaner;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [textarea](http://www.w3schools.com/tags/tag_textarea.asp).
 */
class TextAreaControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($name)
  {
    parent::__construct($name);

    // By default whitespace is trimmed from textarea form controls.
    $this->cleaner = TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $this->attributes['name'] = $this->submitName;

    $html = $this->prefix;
    $html .= Html::generateElement('textarea', $this->attributes, $this->value);
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [cols](http://www.w3schools.com/tags/att_textarea_cols.asp).
   *
   * @param int $value The attribute value.
   */
  public function setAttrCols($value)
  {
    $this->attributes['cols'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [rows](http://www.w3schools.com/tags/att_textarea_rows.asp).
   *
   * @param int $value The attribute value.
   */
  public function setAttrRows($value)
  {
    $this->attributes['rows'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [wrap](http://www.w3schools.com/tags/att_textarea_wrap.asp). Possible values:
   * * soft
   * * hard
   *
   * @param int $value The attribute value.
   */
  public function setAttrWrap($value)
  {
    $this->attributes['wrap'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    // Get the submitted value and cleaned (if required).
    if ($this->cleaner)
    {
      $new_value = $this->cleaner->clean($submittedValue[$submit_name]);
    }
    else
    {
      $new_value = $submittedValue[$submit_name];
    }

    if ((string)$this->value!==(string)$new_value)
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $new_value;
    }

    $whiteListValue[$this->name] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
