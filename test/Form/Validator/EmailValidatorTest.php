<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\EmailValidator;

//----------------------------------------------------------------------------------------------------------------------
class ValidatorEmailTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual email address must be invalid.
   */
  public function testInvalidEmail1()
  {
    $_POST['email'] = '"much.more unusual"@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual email address must be invalid.
   */
  public function testInvalidEmail2()
  {
    $_POST['email'] = '"very.unusual.@.unusual.com"@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A strange but valid email address must be valid.
   */
  public function testInvalidEmail3()
  {
    $_POST['email'] = '!#$%&\'*+-/=?^_`{}|~@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Localhost is not a valid domain part.
   */
  public function testInvalidEmail4()
  {
    $_POST['email'] = 'info@localhost';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats1()
  {
    $_POST['email'] = 'info@info@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats2()
  {
    $_POST['email'] = 'info@setbased.nl@info';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address without an existing A or MX record is invalid.
   */
  public function testInvalidEmailWithNoExistantDomain()
  {
    $_POST['email'] = 'info@xsetbased.nl';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address with a to long local part must be invalid. The maximum length of the local part is 64 characters,
   * see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testInvalidEmailWithToLongLocalPart()
  {
    $local          = str_repeat('x', 65);
    $_POST['email'] = "$local@setbased.nl";
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An @ character must separate local and domain part.
   */
  public function testInvalidEmailWithoutAt()
  {
    $_POST['email'] = 'info.setbased.nl';
    $form           = $this->setupForm1();

    $this->assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail1()
  {
    $_POST['email'] = 'info@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail2()
  {
    $_POST['email'] = 'p.r.water@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail3()
  {
    $_POST['email'] = 'disposable.style.email.with+symbol@setbased.nl';
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailEmpty()
  {
    $_POST['email'] = '';
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailFalse()
  {
    $_POST['email'] = false;
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailNull()
  {
    $_POST['email'] = null;
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address with a long domain part must be valid. The maximum length of the domain part is 255 characters,
   * see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testValidEmailWithLongDomain()
  {
    $_POST['email'] = 'info@thelongestdomainnameintheworldandthensomeandthensomemoreandmore.com';
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address with a long local part must be valid. The maximum length of the local part is 64 characters,
   * see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testValidEmailWithLongLocalPart()
  {
    $local          = str_repeat('x', 64);
    $_POST['email'] = "$local@setbased.nl";
    $form           = $this->setupForm1();

    $this->assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   */
  private function setupForm1()
  {
    $form = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextControl('email');
    $input->addValidator(new EmailValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

