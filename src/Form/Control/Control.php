<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Validator\CompoundValidator;
use SetBased\Abc\Form\Validator\Validator;
use SetBased\Abc\HtmlElement;
use SetBased\Abc\Obfuscator\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for form controls.
 */
abstract class Control extends HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The list of error messages associated with this form control.
   *
   * @var string[]|null
   */
  protected $errorMessages;

  /**
   * The (local) name of this form control.
   *
   * @var string
   */
  protected $name;

  /**
   * The obfuscator to obfuscate the (submitted) name of this form control.
   *
   * @var Obfuscator
   */
  protected $obfuscator;

  /**
   * The HTML code that will be appended after the HTML code of this form control.
   *
   * @var string
   */
  protected $postfix;

  /**
   * The HTML code that will be inserted before the HTML code of this form control.
   *
   * @var string
   */
  protected $prefix;

  /**
   * The submit name or name in the generated HTMl code of this form control.
   *
   * @var string
   */
  protected $submitName;

  /**
   * The validators that will be used to validate this form control.
   *
   * @var Validator[]
   */
  protected $validators = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $name The (local) name of this form control.
   */
  public function __construct($name)
  {
    if ($name===null || $name===false || $name==='')
    {
      // We consider null, bool(false), and string(0) as empty. In these cases we set the name to '' such that
      // we only have to test against '' using the === operator in other parts of the code.
      $this->name = '';
    }
    else
    {
      // We consider int(0), float(0), string(3) "0.0" as non empty names.
      $this->name = (string)$name;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a compound validator for this form control.
   *
   * @param Validator|CompoundValidator $validator
   */
  public function addValidator($validator)
  {
    $this->validators[] = $validator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  abstract public function generate();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an attribute.
   *
   * @note Depending on the child class the returned value might be different than in the actual generated HTML code
   *       for the following attributes:
   *       <ul>
   *       <li> type
   *       <li> name
   *       <li> value
   *       <li> checked
   *       <li> size
   *       </ul>
   *
   * @param string $name The name of the requested attribute.
   *
   * @return string|null
   */
  public function getAttribute($name)
  {
    return (isset($this->attributes[$name])) ? $this->attributes[$name] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the the error messages of this form control.
   *
   * @param bool $recursive
   *
   * @return string[]|null
   */
  public function getErrorMessages(/** @noinspection PhpUnusedParameterInspection */
    $recursive = false)
  {
    return $this->errorMessages;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control in a table cell.
   *
   * @return string
   */
  public function getHtmlTableCell()
  {
    return '<td class="control">'.$this->generate().'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the local name of this form control
   *
   * @return string
   */
  public function getLocalName()
  {
    return $this->name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param array $values
   */
  public function getSetValuesBase(&$values)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submit name of this form control
   *
   * @return string
   */
  public function getSubmitName()
  {
    return $this->submitName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return mixed
   */
  abstract public function getSubmittedValue();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param mixed $values
   */
  public function mergeValuesBase($values)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   */
  public function setErrorMessage($message)
  {
    $this->errorMessages[] = $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the name of this form control.
   *
   * @param Obfuscator $obfuscator The obfuscator.
   */
  public function setObfuscator($obfuscator)
  {
    $this->obfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is inserted before the HTML code of this form control.
   *
   * @param string $htmlSnippet The HTML prefix.
   */
  public function setPostfix($htmlSnippet)
  {
    $this->postfix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is appended after the HTML code of this form control.
   *
   * @param string $htmlSnippet The HTML postfix.
   */
  public function setPrefix($htmlSnippet)
  {
    $this->prefix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param mixed $values
   */
  public function setValuesBase($values)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values.
   *
   * @param array $submittedValue The submitted values.
   * @param array $whiteListValue The white listed values.
   * @param array $changedInputs  The form controls which values are changed by the form submit.
   *
   * @return void
   */
  abstract protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form control for HTML code generation or loading submitted values.
   *
   * @param string $parentSubmitName The submit name of the parent control.
   */
  protected function prepare($parentSubmitName)
  {
    $this->setSubmitName($parentSubmitName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the name this will be used for this form control when the form is submitted.
   *
   * @param string $parentSubmitName The submit name of the parent form control of this form control.
   */
  protected function setSubmitName($parentSubmitName)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if ($parentSubmitName!=='')
    {
      if ($submit_name!=='') $this->submitName = $parentSubmitName.'['.$submit_name.']';
      else                   $this->submitName = $parentSubmitName;
    }
    else
    {
      $this->submitName = $submit_name;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the validators of this form control.
   *
   * @param array $invalidFormControls The form controls with invalid submitted values.
   *
   * @return bool True if and only if the submitted values are valid.
   */
  abstract protected function validateBase(&$invalidFormControls);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
