<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * @brief Abstract super class for test for TextControl, HiddenControl, and PasswordControl.
 */
abstract class SimpleControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0'.
   */
  public function test1Empty1()
  {
    $name          = 0;
    $_POST['name'] = $name;

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals($name, $values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0.0'.
   */
  public function test1Empty2()
  {
    $name          = '0.0';
    $_POST['name'] = $name;

    $form    = $this->setupForm1('');
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals($name, $values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    $input->setValue('1');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);
    
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<input');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value.
   */
  public function testValid101()
  {
    $name          = 'Set Based IT Consultancy';
    $_POST['name'] = $name;

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals($name, $values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted empty value.
   */
  public function testValid102()
  {
    $name          = 'Set Based IT Consultancy';
    $_POST['name'] = '';

    $form    = $this->setupForm1($name);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEmpty($values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theName
   *
   * @return SimpleControl
   */
  abstract protected function getControl($theName);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control.
   *
   * @param string $theValue The value of the form control
   *
   * @return RawForm
   */
  private function setupForm1($theValue)
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    if (isset($theValue)) $input->setValue($theValue);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

