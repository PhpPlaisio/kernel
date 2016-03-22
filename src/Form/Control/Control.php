<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Validator\CompoundValidator;
use SetBased\Abc\Form\Validator\Validator;
use SetBased\Abc\Misc\HtmlElement;
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
  protected $myErrorMessages;

  /**
   * The (local) name of this form control.
   *
   * @var string
   */
  protected $myName;

  /**
   * The obfuscator to obfuscate the (submitted) name of this form control.
   *
   * @var Obfuscator
   */
  protected $myObfuscator;

  /**
   * The HTML code that will be appended after the HTML code of this form control.
   *
   * @var string
   */
  protected $myPostfix;

  /**
   * The HTML code that will be inserted before the HTML code of this form control.
   *
   * @var string
   */
  protected $myPrefix;

  /**
   * The submit name or name in the generated HTMl code of this form control.
   *
   * @var string
   */
  protected $mySubmitName;

  /**
   * The validators that will be used to validate this form control.
   *
   * @var Validator[]
   */
  protected $myValidators = [];


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theName The (local) name of this form control.
   */
  public function __construct($theName)
  {
    if ($theName===null || $theName===false || $theName==='')
    {
      // We consider null, bool(false), and string(0) as empty. In these cases we set the name to '' such that
      // we only have to test against '' using the === operator in other parts of the code.
      $this->myName = '';
    }
    else
    {
      // We consider int(0), float(0), string(3) "0.0" as non empty names.
      $this->myName = (string)$theName;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a compound validator for this form control.
   *
   * @param Validator|CompoundValidator $theValidator
   */
  public function addValidator($theValidator)
  {
    $this->myValidators[] = $theValidator;
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
   * @param string $theName The name of the requested attribute.
   *
   * @return string|null
   */
  public function getAttribute($theName)
  {
    return (isset($this->myAttributes[$theName])) ? $this->myAttributes[$theName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the the error messages of this form control.
   *
   * @param bool $theRecursiveFlag
   *
   * @return string[]|null
   */
  public function getErrorMessages(/** @noinspection PhpUnusedParameterInspection */
    $theRecursiveFlag = false)
  {
    return $this->myErrorMessages;
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
    return $this->myName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param array $theValues
   */
  public function getSetValuesBase(&$theValues)
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
    return $this->mySubmitName;
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
   * @param mixed $theValues
   */
  public function mergeValuesBase($theValues)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $theMessage The error message.
   */
  public function setErrorMessage($theMessage)
  {
    $this->myErrorMessages[] = $theMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the name of this form control.
   *
   * @param Obfuscator $theObfuscator The obfuscator.
   */
  public function setObfuscator($theObfuscator)
  {
    $this->myObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is inserted before the HTML code of this form control.
   *
   * @param string $theHtmlSnippet The HTML prefix.
   */
  public function setPostfix($theHtmlSnippet)
  {
    $this->myPostfix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is appended after the HTML code of this form control.
   *
   * @param string $theHtmlSnippet The HTML postfix.
   */
  public function setPrefix($theHtmlSnippet)
  {
    $this->myPrefix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param mixed $theValues
   */
  public function setValuesBase($theValues)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values.
   *
   * @param array $theSubmittedValue The submitted values.
   * @param array $theWhiteListValue The white listed values.
   * @param array $theChangedInputs  The form controls which values are changed by the form submit.
   *
   * @return void
   */
  abstract protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form control for HTML code generation or loading submitted values.
   *
   * @param string $theParentSubmitName The submit name of the parent control.
   */
  protected function prepare($theParentSubmitName)
  {
    $this->setSubmitName($theParentSubmitName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the name this will be used for this form control when the form is submitted.
   *
   * @param string $theParentSubmitName The submit name of the parent form control of this form control.
   */
  protected function setSubmitName($theParentSubmitName)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if ($theParentSubmitName!=='')
    {
      if ($submit_name!=='') $this->mySubmitName = $theParentSubmitName.'['.$submit_name.']';
      else                   $this->mySubmitName = $theParentSubmitName;
    }
    else
    {
      $this->mySubmitName = $submit_name;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the validators of this form control.
   *
   * @param array $theInvalidFormControls The form controls with invalid submitted values.
   *
   * @return bool True if and only if the submitted values are valid.
   */
  abstract protected function validateBase(&$theInvalidFormControls);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
