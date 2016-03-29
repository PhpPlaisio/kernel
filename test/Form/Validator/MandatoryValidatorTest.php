<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\HiddenControl;
use SetBased\Abc\Form\Control\PasswordControl;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\Control\TextAreaControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\MandatoryValidator;

//----------------------------------------------------------------------------------------------------------------------
class MandatoryValidatorTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory text, password, hidden or textarea form control with value @c null, @c false, or @c '', is invalid.
   */
  public function testInvalidEmpty()
  {
    $types  = ['text', 'password', 'hidden', 'textarea', 'checkbox'];
    $values = [null, false, ''];

    foreach ($types as $type)
    {
      foreach ($values as $value)
      {

        $_POST['input'] = $value;
        $control        = null;
        switch ($type)
        {
          case 'text':
            $control = new TextControl('input');
            break;

          case 'password':
            $control = new PasswordControl('input');
            break;

          case 'hidden':
            $control = new HiddenControl('input');
            break;

          case 'textarea':
            $control = new TextAreaControl('input');
            break;

          case 'checkbox':
            $control = new CheckboxControl('input');
            break;
        }
        $form = $this->setupForm1($control);

        $this->assertFalse($form->validate(),
                           sprintf("type: '%s', value: '%s'.", $type, var_export($value, true)));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory unchecked checked box is invalid.
   */
  public function testInvalidUncheckedCheckbox()
  {
    $_POST = [];
    $form  = $this->setupForm2();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory text, password, or textarea form control with whitespace is invalid.
   */
  public function testInvalidWhitespace()
  {
    $types  = ['text', 'password', 'textarea'];
    $values = [' ', '  ', " \n  "];

    foreach ($types as $type)
    {
      foreach ($values as $value)
      {

        $_POST['input'] = $value;
        $control        = null;
        switch ($type)
        {
          case 'text':
            $control = new TextControl('input');
            break;

          case 'password':
            $control = new PasswordControl('input');
            break;

          case 'textarea':
            $control = new TextAreaControl('input');
            break;
        }
        $form = $this->setupForm1($control);

        $this->assertFalse($form->validate(),
                           sprintf("type: '%s', value: '%s'.", $type, var_export($value, true)));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory checked checked box is valid.
   */
  public function testValidCheckedCheckbox()
  {
    $_POST['box'] = 'on';
    $form         = $this->setupForm2();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory non-empty text, password, hidden, or textarea form control is valid.
   */
  public function testValidNoneEmptyText()
  {
    $types = ['text', 'password', 'hidden', 'textarea'];

    foreach ($types as $type)
    {
      $_POST['input'] = 'Set Based IT Consultancy';
      $control        = null;
      switch ($type)
      {
        case 'text':
          $control = new TextControl('input');
          break;

        case 'password':
          $control = new PasswordControl('input');
          break;

        case 'hidden':
          $control = new HiddenControl('input');
          break;

        case 'textarea':
          $control = new TextAreaControl('input');
          break;
      }
      $form = $this->setupForm1($control);

      $this->assertTrue($form->validate(), sprintf("type: '%s'.", $type));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a single form control of certain type.
   *
   * @param SimpleControl $theControl
   *
   * @return RawForm
   */
  private function setupForm1($theControl)
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = $theControl;
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a checkbox form control.
   */
  private function setupForm2()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('box');
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes
  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

