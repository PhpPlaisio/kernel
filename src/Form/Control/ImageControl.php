<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:image](http://www.w3schools.com/tags/tag_input.asp).
 */
class ImageControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type'] = 'image';
    $this->attributes['name'] = $this->mySubmitName;

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [alt](http://www.w3schools.com/tags/att_input_alt.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttr($theValue)
  {
    $this->attributes['alt'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formaction](http://www.w3schools.com/tags/att_input_formaction.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormAction($theValue)
  {
    $this->attributes['formaction'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formenctype](http://www.w3schools.com/tags/att_input_formenctype.asp). Possible values:
   * * application/x-www-form-urlencoded (default)
   * * multipart/form-data
   * * text/plain
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormEncType($theValue)
  {
    $this->attributes['formenctype'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formmethod](http://www.w3schools.com/tags/att_input_formmethod.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormMethod($theValue)
  {
    $this->attributes['formmethod'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formtarget](http://www.w3schools.com/tags/att_input_formtarget.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormTarget($theValue)
  {
    $this->attributes['formtarget'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [height](http://www.w3schools.com/tags/att_input_height.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrHeight($theValue)
  {
    $this->attributes['height'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [src](http://www.w3schools.com/tags/att_input_src.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrSrc($theValue)
  {
    $this->attributes['src'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [width](http://www.w3schools.com/tags/att_input_width.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrWidth($theValue)
  {
    $this->attributes['width'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string|bool $theValue .
   */
  public function setValue($theValue)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    throw new LogicException('Not implemented.');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
