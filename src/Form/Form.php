<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SilentControl;
use SetBased\Exception\LogicException;
use SetBased\Exception\RuntimeException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for forms with protection against CSRF.
 *
 * This form class protects against CSRF attacks by means of State Full Double Submit Cookie.
 */
class Form extends RawForm
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set the generated form has protection against CSRF.
   *
   * @var bool
   */
  protected $enableCsrfCheck;

  /**
   * FieldSet for all form control elements of type "hidden".
   *
   * @var FieldSet
   */
  protected $hiddenFieldSet;

  /**
   * The handlers for handling submits of this form.
   *
   * @var array
   */
  protected $submitHandlers = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $name
   * @param bool   $csrfCheckFlag If set the generated form has protection against CSRF.
   */
  public function __construct($name = '', $csrfCheckFlag = true)
  {
    parent::__construct($name);

    $this->enableCsrfCheck = $csrfCheckFlag;

    // Create a fieldset for hidden form controls.
    $this->hiddenFieldSet = new FieldSet('');
    $this->addFieldSet($this->hiddenFieldSet);

    // Set attribute for name (used by JavaScript).
    if ($name!=='') $this->setAttrData('name', $name);

    // Add hidden field for protection against CSRF.
    if ($this->enableCsrfCheck) $this->hiddenFieldSet->addFormControl(new SilentControl('ses_csrf_token'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test whether a form control is submitted.
   *
   * @param string $submitName The submit name of the form control.
   *
   * @return mixed
   */
  private static function testSubmitted($submitName)
  {
    $parts = explode('[', str_replace(']', '', $submitName));

    $ret = $_POST;
    foreach ($parts as $part)
    {
      if (!isset($ret[$part]))
      {
        $ret = null;
        break;
      }
      else
      {
        $ret = $ret[$part];
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a hidden form control to the fieldset for hidden form controls.
   *
   * @param Control $control The hidden form control.
   */
  public function addHiddenFormControl($control)
  {
    $this->hiddenFieldSet->addFormControl($control);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Appends an event handler for form submit.
   *
   * @param Control $control The form control that submits the form.
   * @param string  $method  The method for handling the form submit.
   */
  public function addSubmitHandler($control, $method)
  {
    $this->submitHandlers[] = ['control' => $control,
                               'method'  => $method];
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Defends against CSRF attacks using State Full Double Submit Cookie.
   *
   * @throws RuntimeException
   */
  public function csrfCheck()
  {
    // Return immediately if CSRF check is disabled.
    if (!$this->enableCsrfCheck) return;

    $control = $this->hiddenFieldSet->getFormControlByName('ses_csrf_token');

    // If CSRF tokens (from session and from submitted form) don't match: possible CSRF attack.
    $ses_csrf_token1 = Abc::getInstance()->getCsrfToken();
    $ses_csrf_token2 = $control->getSubmittedValue();
    if ($ses_csrf_token1!==$ses_csrf_token2)
    {
      throw new RuntimeException('Possible CSRF attack.');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default form handler. It only handles method 'handleEchoForm'. Otherwise an exception is thrown.
   *
   * @param string $method The name of the method for handling the form state.
   */
  public function defaultHandler($method)
  {
    switch ($method)
    {
      case 'handleEchoForm':
        $this->handleEchoForm();
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes this form. Executes means:
   * <ul>
   * <li> If the form is submitted the submitted values are validated:
   *      <ul>
   *      <li> If the submitted values are valid the appropriated handler is returned.
   *      <li> Otherwise the form is shown.
   *      </ul>
   * <li> Otherwise the form is shown.
   * </ul>
   *
   * @return string|null The appropriate handler method.
   */
  public function execute()
  {
    // Prepare the form.
    $this->prepare();

    // Is this form submitted?
    // @todo implement event types
    // @todo implement submit without button (i.e. submit via JS)
    $handler   = null;
    $submitted = null;
    foreach ($this->submitHandlers as $handler)
    {
      /** @var Control $control */
      $control = $handler['control'];
      if (self::testSubmitted($control->getSubmitName()))
      {
        $submitted = true;
        break;
      }
    }

    // @todo implement dependant controls.

    if ($submitted)
    {
      $this->loadSubmittedValues();
      $valid = $this->validate();
      if (!$valid)
      {
        $method = 'handleEchoForm';
      }
      else
      {
        $method = $handler['method'];
      }
    }
    else
    {
      $method = 'handleEchoForm';
    }

    return $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the hidden fieldset of this form.
   *
   * @return FieldSet
   */
  public function getHiddenFieldSet()
  {
    return $this->hiddenFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values and protects against CSRF attacks using State Full Double Submit Cookie.
   *
   * The white listed values can be obtained with method {@link getValues).
   */
  public function loadSubmittedValues()
  {
    parent::loadSubmittedValues();

    $this->csrfCheck();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates and echos this form.
   *
   * This is the default method for generating and echoing a form.
   */
  protected function handleEchoForm()
  {
    echo $this->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
