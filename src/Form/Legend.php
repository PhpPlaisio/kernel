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
  protected $legend;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this legend.
   *
   * @return string
   */
  public function generate()
  {
    return Html::generateElement('legend', $this->attributes, $this->legend, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string $html The HTML of legend. It is the developer's responsibility that it is valid HTML code.
   */
  public function setLegendHtml($html)
  {
    $this->legend = $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string $text The text of legend. Special characters will be converted to HTML entities.
   */
  public function setLegendText($text)
  {
    $this->legend = Html::txt2Html($text);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
