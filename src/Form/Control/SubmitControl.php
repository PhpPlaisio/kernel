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
   * @param string $name
   */
  public function __construct($name)
  {
    parent::__construct($name);

    $this->buttonType = 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formaction](http://www.w3schools.com/tags/att_input_formaction.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrFormAction($value)
  {
    $this->attributes['formaction'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formenctype](http://www.w3schools.com/tags/att_input_formenctype.asp). Possible values:
   * * application/x-www-form-urlencoded (default)
   * * multipart/form-data
   * * text/plain
   *
   * @param string $value The attribute value.
   */
  public function setAttrFormEncType($value)
  {
    $this->attributes['formenctype'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formmethod](http://www.w3schools.com/tags/att_input_formmethod.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string $value The attribute value.
   */
  public function setAttrFormMethod($value)
  {
    $this->attributes['formmethod'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formtarget](http://www.w3schools.com/tags/att_input_formtarget.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrFormTarget($value)
  {
    $this->attributes['formtarget'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
