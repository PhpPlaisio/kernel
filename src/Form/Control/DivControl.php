<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A class for pseudo form controls for generating [div](http://www.w3schools.com/tags/tag_div.asp) elements inside
 * forms.
 */
class DivControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The inner HTML code of this div element.
   */
  protected $myInnerHtml;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $html = $this->myPrefix;
    $html .= Html::generateElement('div', $this->myAttributes, $this->myInnerHtml, true);
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null.
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this div element.
   *
   * @param string $theHtmlSnippet The inner HTML. It is the developer's responsibility that it is valid HTML code.
   */
  public function setInnerHtml($theHtmlSnippet)
  {
    $this->myInnerHtml = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this div element.
   *
   * @param string $theText The inner HTML. Special characters will be converted to HTML entities.
   */
  public function setInnerText($theText)
  {
    $this->myInnerHtml = Html::txt2Html($theText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase(&$theInvalidFormControls)
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
