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
  public function __construct($theName)
  {
    parent::__construct($theName);

    // By default whitespace is trimmed from textarea form controls.
    $this->myCleaner = TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $this->attributes['name'] = $this->mySubmitName;

    $html = $this->myPrefix;
    $html .= Html::generateElement('textarea', $this->attributes, $this->myValue);
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [cols](http://www.w3schools.com/tags/att_textarea_cols.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrCols($theValue)
  {
    $this->attributes['cols'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [rows](http://www.w3schools.com/tags/att_textarea_rows.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrRows($theValue)
  {
    $this->attributes['rows'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [wrap](http://www.w3schools.com/tags/att_textarea_wrap.asp). Possible values:
   * * soft
   * * hard
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrWrap($theValue)
  {
    $this->attributes['wrap'] = $theValue;
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

    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
