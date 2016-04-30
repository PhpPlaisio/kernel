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
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = $this->value;

    $html = $this->prefix;
    $html .= $this->generatePrefixLabel();
    $html .= Html::generateVoidElement('input', $this->attributes);
    $html .= $this->generatePostfixLabel();
    $html .= $this->postfix;

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
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    /** @todo Decide whether to test submitted value is white listed, i.e. $this->myAttributes['value'] (or 'on'
     *  if $this->myAttributes['value'] is null) or null.
     */

    if (empty($this->value)!==empty($submittedValue[$submit_name]))
    {
      $changedInputs[$this->name] = $this;
    }

    if (!empty($submittedValue[$submit_name]))
    {
      $this->value                 = true;
      $whiteListValue[$this->name] = true;
    }
    else
    {
      $this->value                 = false;
      $whiteListValue[$this->name] = false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
