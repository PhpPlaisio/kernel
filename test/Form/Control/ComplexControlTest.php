<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\MandatoryValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ButtonControlTest
 */
class ComplexControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var ComplexControl
   */
  private $myOriginComplexControl;

  /**
   * @var SimpleControl
   */
  private $myOriginControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test find FormControl by name.
   */
  public function testFindFormControlByName()
  {
    $form = $this->setForm1();

    // Find form control by name. Must return object.
    $input = $form->findFormControlByName('street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    // Find form control by name what does not exist. Must return null.
    $input = $form->findFormControlByName('not_exists');
    $this->assertEquals(null, $input);

    $input = $form->findFormControlByName('/no_path/not_exists');
    $this->assertEquals(null, $input);

    $input = $form->findFormControlByName('/vacation/not_exists');
    $this->assertEquals(null, $input);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test find FormControl by path.
   */
  public function testFindFormControlByPath()
  {
    $form = $this->setForm1();

    // Find form control by path. Must return object.
    $input = $form->findFormControlByPath('/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/post/zip-code');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/vacation/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);


    // Find form control by path what does not exist. Must return null.
    $input = $form->findFormControlByPath('/not_exists');
    $this->assertEquals(null, $input);

    $input = $form->findFormControlByPath('/no_path/not_exists');
    $this->assertEquals(null, $input);

    $input = $form->findFormControlByPath('/vacation/not_exists');
    $this->assertEquals(null, $input);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by name.
   */
  public function testGetFormControlByName()
  {
    $form = $this->setForm1();

    // Get form control by name. Must return object.
    $input = $form->getFormControlByName('vacation');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $input->getFormControlByName('city2');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);
    $this->assertEquals('city2', $input->getLocalName());
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by path.
   */
  public function testGetFormControlByPath()
  {
    $form = $this->setForm1();

    // Get form control by path. Must return object.
    $input = $form->getFormControlByPath('/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/vacation/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName1()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName2()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('/no_path/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName3()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath1()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath2()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('street');
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath3()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/no_path/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath4()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   */
  public function testSubmitValues()
  {
    $_POST['field_1']                                  = 'value';
    $_POST['field_3']                                  = 'value';
    $_POST['complex_name']['field_2']                  = 'value';
    $_POST['complex_name']['complex_name2']['field_4'] = 'value';

    $form = $this->setForm2();
    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayHasKey('field_1', $values);
    $this->assertArrayHasKey('field_2', $values['complex_name']);

    $this->assertArrayHasKey('field_3', $values['complex_name']);
    $this->assertArrayHasKey('field_4', $values['complex_name']['complex_name2']);

    $this->assertNotEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with alphanumeric name of the control in the complex control.
   */
  public function testValid4()
  {
    $names = ['test01', 10, 0, '0.0'];

    foreach ($names as $name)
    {
      // Create form with control inside of complex control.
      $form = $this->setForm3($name);

      // Firs find complex control by name.
      $complex_control = $form->findFormControlByName($name);

      // Test for complex control.
      $this->assertNotEmpty($complex_control);
      $this->assertEquals($this->myOriginComplexControl, $complex_control);
      $this->assertEquals($name, $complex_control->getLocalName());

      // Find control by name.
      $input = $complex_control->findFormControlByName($name);

      // Test for control.
      $this->assertNotEmpty($input);
      $this->assertEquals($this->myOriginControl, $input);
      $this->assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Each form controls in form must validate and add to invalid controls if it not valid.
   */
  public function testValidate()
  {
    $form = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    // Create mandatory control.
    $input = new CheckboxControl('input_1');
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    // Create optional control.
    $input = new CheckboxControl('input_2');
    $fieldset->addFormControl($input);

    // Create mandatory control.
    $input = new CheckboxControl('input_3');
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    // Simulate a post without any values.
    $form->loadSubmittedValues();
    $form->validate();
    $invalid = $form->getInvalidControls();

    // We expect 2 invalid controls.
    $this->assertCount(2, $invalid);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteList()
  {
    $_POST['unknown_field']                    = 'value';
    $_POST['unknown_complex']['unknown_field'] = 'value';

    $form = $this->setForm2();
    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayNotHasKey('unknown_field', $values);
    $this->assertArrayNotHasKey('unknown_complex', $values);

    $this->assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    
    $complex = new ComplexControl('');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);


    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);


    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('code');
    $complex->addFormControl($input);

    $input = new TextControl('state');
    $complex->addFormControl($input);


    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('zip-code');
    $complex->addFormControl($input);

    $input = new TextControl('state');
    $complex->addFormControl($input);


    $fieldset = new FieldSet('vacation');
    $form->addFieldSet($fieldset);


    $complex = new ComplexControl('');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);


    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);


    $complex = new ComplexControl('');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street2');
    $complex->addFormControl($input);

    $input = new TextControl('city2');
    $complex->addFormControl($input);

    $form->prepare();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm2()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);


    $complex1 = new ComplexControl('');
    $fieldset->addFormControl($complex1);

    $input = new TextControl('field_1');
    $complex1->addFormControl($input);


    $complex1 = new ComplexControl('complex_name');
    $fieldset->addFormControl($complex1);

    $input = new TextControl('field_2');
    $complex1->addFormControl($input);


    $complex2 = new ComplexControl('');
    $complex1->addFormControl($complex2);

    $input = new TextControl('field_3');
    $complex2->addFormControl($input);


    $complex3 = new ComplexControl('complex_name2');
    $complex2->addFormControl($complex3);

    $input = new TextControl('field_4');
    $complex3->addFormControl($input);

    $form->prepare();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form with specific name of control.
   *
   * @param string $theName The name of the form control.
   *
   * @return RawForm
   */
  private function setForm3($theName)
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl($theName);
    $fieldset->addFormControl($complex);

    $input = new TextControl($theName);
    $complex->addFormControl($input);

    $this->myOriginComplexControl = $complex;
    $this->myOriginControl        = $input;

    $form->prepare();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
