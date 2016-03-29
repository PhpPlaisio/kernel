<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\CheckboxesControl;
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class FormTest
 */
class FormTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex control with different types of names.
   */
  public function testFindComplexControl()
  {
    $names = ['hello', 10, 0, '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind('', $name);

      $input = $form->findFormControlByName($name);
      $this->assertNotEmpty($input);
      $this->assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a fieldset with different types of names.
   */
  public function testFindFieldSet()
  {
    $names = ['hello', 10, 0, '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind($name);

      $input = $form->findFormControlByName($name);
      $this->assertNotEmpty($input);
      $this->assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex with with different types of names.
   */
  public function testFindSimpleControl()
  {
    $names = ['hello', 10, 0, '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind('', 'post', $name);

      $input = $form->findFormControlByName($name);
      $this->assertNotEmpty($input);
      $this->assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for getCurrentValues values.
   */
  public function testGetSetValues()
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 3, 'label' => 'label3'];

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);
      
    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);
    
    $values['name1']      = 'name1';
    $values['name2']      = 'name2';
    $values['options'][1] = true;
    $values['options'][2] = false;
    $values['options'][3] = true;

    // Set the form control values.
    $form->setValues($values);

    $current = $form->getSetValues();

    $this->assertEquals('name1', $current['name1']);
    $this->assertEquals('name2', $current['name2']);
    $this->assertTrue($current['options'][1]);
    $this->assertFalse($current['options'][2]);
    $this->assertTrue($current['options'][3]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars1()
  {
    $_POST = [];

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars($changed);

    $this->assertFalse($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars2()
  {
    $_POST          = [];
    $_POST['name1'] = 'Hello world';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars($changed);

    $this->assertTrue($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars3()
  {
    $_POST              = [];
    $_POST['name1']     = 'Hello world';
    $_POST['option'][2] = 'on';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars($changed);

    $this->assertTrue($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars4()
  {
    $_POST              = [];
    $_POST['option'][2] = 'on';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars($changed);

    $this->assertFalse($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for merging values.
   */
  public function testMergeValues()
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 3, 'label' => 'label3'];

    $form     = new RawForm();
    $fieldset = new FieldSet('name');
    $form->addFieldSet($fieldset);

    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);
    
    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $values['name']['name1']      = 'name1';
    $values['name']['name2']      = 'name2';
    $values['name']['options'][1] = true;
    $values['name']['options'][2] = false;
    $values['name']['options'][3] = true;

    $merge['name']['name1']      = 'NAME1';
    $merge['name']['options'][2] = true;
    $merge['name']['options'][3] = null;

    // Set the form control values.
    $form->setValues($values);

    // Override few form control values.
    $form->mergeValues($merge);

    // Generate HTML.
    $form->prepare();
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML($html);
    $xpath = new DOMXpath($doc);

    // name[name1] must be overridden.
    $list = $xpath->query("/form/fieldset/input[@name='name[name1]' and @value='NAME1']");
    $this->assertEquals(1, $list->length);

    // name[name2] must be unchanged.
    $list = $xpath->query("/form/fieldset/input[@name='name[name2]' and @value='name2']");
    $this->assertEquals(1, $list->length);

    // name[options][1] must be unchanged.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][1]' and @checked='checked']");
    $this->assertEquals(1, $list->length);

    // name[options][2] must be changed.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][2]' and @checked='checked']");
    $this->assertEquals(1, $list->length);

    // name[options][3] must be changed.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][3]' and not(@checked)]");
    $this->assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return RawForm
   */
  private function setupForm1()
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 2, 'label' => 'label3'];

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);
    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theFieldSetName
   * @param string $theComplexControlName
   * @param string $theTextControlName
   *
   * @return RawForm
   */
  private function setupFormFind($theFieldSetName = 'vacation',
                                 $theComplexControlName = 'post',
                                 $theTextControlName = 'city'
  )
  {
    $form     = new RawForm();
    $fieldset = new FieldSet($theFieldSetName);
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


    $complex = new ComplexControl($theComplexControlName);
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl($theTextControlName);
    $complex->addFormControl($input);


    $complex = new ComplexControl('');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street2');
    $complex->addFormControl($input);
    $input = new TextControl('city2');
    $complex->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
