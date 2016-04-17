<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp), however the submitted
 * value is never loaded.
 */
class InvisibleControl extends SimpleControl
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
   * An invisible control must never be shown in a table.
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
   *
   * Note:
   * Always sets the white listed value to the value of this constant form control.
   * Never uses $theSubmittedValue and never sets the $theChangedInputs.
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    // Note: by definition the value of a input:invisible form control will not be changed, whatever is submitted.
    $theWhiteListValue[$this->myName] = $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
