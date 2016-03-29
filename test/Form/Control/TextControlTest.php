<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Cleaner\DateCleaner;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Formatter\DateFormatter;
use SetBased\Abc\Form\RawForm;

class TextControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testDateFormattingAndCleaning()
  {
    $_POST['birthday'] = '10.04.1966';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    
    $input = new TextControl('birthday');
    $input->setValue('1966-04-10');
    $input->setCleaner(new DateCleaner('d-m-Y', '-', '/-. '));
    $input->setFormatter(new DateFormatter('d-m-Y'));
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After formatting and clean the date must be in ISO 8601 format.
    $this->assertEquals('1966-04-10', $values['birthday']);

    // Effectively the date is not changed.
    $this->assertArrayNotHasKey('birthday', $changed);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testPruneWhitespaceNoChanged()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    
    $input =  new TextControl('test');
    $input->setValue('Hello World!');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    $this->assertEquals('Hello World!', $values['test']);

    // Effectively the value is not changed.
    $this->assertArrayNotHasKey('test', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getControl($theName)
  {
    return new TextControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
