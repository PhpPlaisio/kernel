<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:submit](http://www.w3schools.com/tags/tag_input.asp).
 */
class SubmitControl extends PushMeControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    $this->myButtonType = 'submit';
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
}

//----------------------------------------------------------------------------------------------------------------------
