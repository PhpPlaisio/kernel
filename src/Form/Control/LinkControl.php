<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A class for pseudo form controls for generating [hyperlink](http://www.w3schools.com/tags/tag_a.asp) elements inside
 * forms.
 */
class LinkControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The inner HTML code of this hyperlink element.
   */
  protected $myInnerHtml;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $ret = $this->myPrefix;
    $ret .= Html::generateElement('a', $this->attributes, $this->myInnerHtml, true);
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null;
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this hyperlink element.
   *
   * @param string $theHtmlSnippet The inner HTML. It is the developer's responsibility that it is valid HTML code.
   */
  public function setInnerHtml($theHtmlSnippet)
  {
    $this->myInnerHtml = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this hyperlink element.
   *
   * @param string $theText The inner text. Special characters will be converted to HTML entities.
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
   * Returns always true.
   *
   * @param array $theInvalidFormControls Not used.
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
