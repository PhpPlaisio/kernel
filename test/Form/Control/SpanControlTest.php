<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
class SpanControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new SpanControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);
    
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<span>');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '</span>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

