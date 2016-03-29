<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class CheckboxControlTest Test class for testing SatBased\Html\Form\CheckboxControl class.
 */
class CheckboxControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test prefix and postfix labels.
   */
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('name');
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
   * Test submit value.
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    
    $input = new CheckboxControl('test1');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value has not set.
    $this->assertFalse($values['test1']);
    // Value has not change.
    $this->assertArrayNotHasKey('test1', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue2()
  {
    $_POST['test2'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    
    $input = new CheckboxControl('test2');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    $this->assertTrue($values['test2']);

    // Assert value has changed.
    $this->assertNotEmpty($changed['test2']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form checked.
   * In POST unchecked.
   */
  public function testSubmittedValue3()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    
    $input = new CheckboxControl('test3');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST checkbox unchecked.
    $this->assertFalse($values['test3']);

    // Value is change.
    $this->assertNotEmpty($changed['test3']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue4()
  {
    $_POST['test4'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test4');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    $this->assertTrue($values['test4']);

    // Value has not changed.
    $this->assertArrayNotHasKey('test4', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------
