<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\HtmlElement;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for generating [legend](http://www.w3schools.com/tags/tag_legend.asp) elements.
 */
class Legend extends HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The inner HTML snippet of this legend.
   */
  protected $myLegend;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this legend.
   *
   * @return string
   */
  public function generate()
  {
    return Html::generateElement('legend', $this->attributes, $this->myLegend, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string $theHtml The HTML of legend. It is the developer's responsibility that it is valid HTML code.
   */
  public function setLegendHtml($theHtml)
  {
    $this->myLegend = $theHtml;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string $theText The text of legend. Special characters will be converted to HTML entities.
   */
  public function setLegendText($theText)
  {
    $this->myLegend = Html::txt2Html($theText);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
