<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:checkbox](http://www.w3schools.com/tags/tag_input.asp).
 *
 * @todo    Add attribute for label.
 */
class CheckboxControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of this form control.
   *
   * @var bool
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->mySubmitName;
    $this->attributes['checked'] = $this->myValue;

    $html = $this->myPrefix;
    $html .= $this->generatePrefixLabel();
    $html .= Html::generateVoidElement('input', $this->attributes);
    $html .= $this->generatePostfixLabel();
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control in a table cell.
   *
   * @return string
   */
  public function getHtmlTableCell()
  {
    return '<td class="control checkbox">'.$this->generate().'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    /** @todo Decide whether to test submitted value is white listed, i.e. $this->myAttributes['value'] (or 'on'
     *  if $this->myAttributes['value'] is null) or null.
     */

    if (empty($this->myValue)!==empty($theSubmittedValue[$submit_name]))
    {
      $theChangedInputs[$this->myName] = $this;
    }

    if (!empty($theSubmittedValue[$submit_name]))
    {
      $this->myValue                    = true;
      $theWhiteListValue[$this->myName] = true;
    }
    else
    {
      $this->myValue                    = false;
      $theWhiteListValue[$this->myName] = false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
