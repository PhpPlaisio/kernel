<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Formatter\Formatter;
use SetBased\Abc\Helper\Html;
use SetBased\Affirm\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for form controls of type:
 * <ul>
 * <li> text
 * <li> password
 * <li> hidden
 * <li> checkbox
 * <li> radio
 * <li> submit
 * <li> reset
 * <li> button
 * <li> file
 * <li> image
 * </ul>
 */
abstract class SimpleControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   *
   * @var Cleaner
   */
  protected $myCleaner;

  /**
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var Formatter
   */
  protected $myFormatter;

  /**
   * The label of this form control.
   *
   * @var string A HTML snippet.
   */
  protected $myLabel;

  /**
   * The attributes for the label of this form control.
   *
   * @var string[]
   */
  protected $myLabelAttributes = [];

  /**
   * The position of the label of this form control.
   * <ul>
   * <li> 'pre'  The label will be inserted before the HML code of this form control.
   * <li> 'post' The label will be appended after the HML code of this form control.
   * </ul>
   *
   * @var string|null
   */
  protected $myLabelPosition;

  /**
   * The value of this form control.
   *
   * @var string
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theName The name of the form control.
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    // A simple form control must have a name.
    if ($this->myName==='')
    {
      throw new LogicException('Name is empty');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of this form control to the values.
   *
   * @param array $theValues
   */
  public function getSetValuesBase(&$theValues)
  {
    $theValues[$this->myName] = $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return string
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theValues
   */
  public function mergeValuesBase($theValues)
  {
    if (array_key_exists($this->myName, $theValues))
    {
      $this->setValuesBase($theValues);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_input_autocomplete.asp).
   * * Any value that evaluates to true will set the attribute to 'on'.
   * * Any value that evaluates to false will set the attribute to 'off'.
   * * Null will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrAutoComplete($theValue)
  {
    $this->myAttributes['autocomplete'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autofocus](http://www.w3schools.com/tags/att_input_autofocus.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'autofocus'. Any other value will unset the attribute.
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrAutoFocus($theValue)
  {
    $this->myAttributes['autofocus'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [disabled](http://www.w3schools.com/tags/att_input_disabled.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'disabled'. Any other value will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrDisabled($theValue)
  {
    $this->myAttributes['disabled'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [form](http://www.w3schools.com/tags/att_input_form.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrForm($theValue)
  {
    $this->myAttributes['form'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [list](http://www.w3schools.com/tags/att_input_list.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrList($theValue)
  {
    $this->myAttributes['list'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [max](http://www.w3schools.com/tags/att_input_max.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrMax($theValue)
  {
    $this->myAttributes['max'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [maxlength](http://www.w3schools.com/tags/att_input_maxlength.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrMaxLength($theValue)
  {
    $this->myAttributes['maxlength'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [min](http://www.w3schools.com/tags/att_input_min.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrMin($theValue)
  {
    $this->myAttributes['min'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [multiple](http://www.w3schools.com/tags/att_input_multiple.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'multiple'. Any other value will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrMultiple($theValue)
  {
    $this->myAttributes['multiple'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [pattern](http://www.w3schools.com/tags/att_input_pattern.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrPattern($theValue)
  {
    $this->myAttributes['pattern'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [placeholder](http://www.w3schools.com/tags/att_input_placeholder.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrPlaceHolder($theValue)
  {
    $this->myAttributes['placeholder'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [readonly](http://www.w3schools.com/tags/att_input_readonly.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'readonly'. Any other value will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrReadOnly($theValue)
  {
    $this->myAttributes['readonly'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [required](http://www.w3schools.com/tags/att_input_required.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'required'. Any other value will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrRequired($theValue)
  {
    $this->myAttributes['required'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [size](http://www.w3schools.com/tags/att_input_size.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrSize($theValue)
  {
    $this->myAttributes['size'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [step](http://www.w3schools.com/tags/att_input_step.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrStep($theValue)
  {
    $this->myAttributes['step'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner $theCleaner The cleaner.
   */
  public function setCleaner($theCleaner)
  {
    $this->myCleaner = $theCleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the formatter for this form control.
   *
   * @param Formatter $theFormatter The formatter.
   */
  public function setFormatter($theFormatter)
  {
    $this->myFormatter = $theFormatter;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of an attribute the label for this form control.
   *
   * The attribute is unset when the value is one of:
   * <ul>
   * <li> null
   * <li> false
   * <li> ''.
   * </ul>
   *
   * If attribute name is 'class' then the value is appended to the space separated list of classes.
   *
   * @param string $theName  The name of the attribute.
   * @param string $theValue The value for the attribute.
   */
  public function setLabelAttribute($theName, $theValue)
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      unset($this->myLabelAttributes[$theName]);
    }
    else
    {
      if ($theName=='class' && isset($this->myLabelAttributes[$theName]))
      {
        $this->myLabelAttributes[$theName] .= ' ';
        $this->myLabelAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myLabelAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the label for this form control.
   *
   * @param string $theHtmlSnippet The (inner) label HTML snippet. It is the developer's responsibility that it is
   *                               valid HTML code.
   */
  public function setLabelHtml($theHtmlSnippet)
  {
    $this->myLabel = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the position of the label of this form control.
   * <ul>
   * <li> 'pre'  The label will be inserted before the HML code of this form control.
   * <li> 'post' The label will be appended after the HML code of this form control.
   * <li> null No label will be generated for this form control.
   * </ul>
   *
   * @param string|null $thePosition
   */
  public function setLabelPosition($thePosition)
  {
    $this->myLabelPosition = $thePosition;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the abel for this form control.
   *
   * @param string $theText The (inner) label text. Special characters are converted to HTML entities.
   */
  public function setLabelText($theText)
  {
    $this->myLabel = Html::txt2Html($theText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string $theValue The new value for the form control.
   */
  public function setValue($theValue)
  {
    $this->myValue = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function setValuesBase($theValues)
  {
    $this->setValue(isset($theValues[$this->myName]) ? $theValues[$this->myName] : null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for the label for this form control.
   *
   * @return string
   */
  protected function generateLabel()
  {
    return Html::generateElement('label', $this->myLabelAttributes, $this->myLabel, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for a label for this form control to be appended after the HTML code of this form control.
   *
   * @return string
   */
  protected function generatePostfixLabel()
  {
    // Generate a postfix label, if required.
    if ($this->myLabelPosition=='post')
    {
      $ret = $this->generateLabel();
    }
    else
    {
      $ret = '';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for a label for this form control te be inserted before the HTML code of this form control.
   *
   * @return string
   */
  protected function generatePrefixLabel()
  {
    // If a label must be generated make sure the form control and the label have matching 'id' and 'for' attributes.
    if (isset($this->myLabelPosition))
    {
      if (!isset($this->myAttributes['id']))
      {
        $id                             = Html::getAutoId();
        $this->myAttributes['id']       = $id;
        $this->myLabelAttributes['for'] = $id;
      }
      else
      {
        $this->myLabelAttributes['for'] = $this->myAttributes['id'];
      }
    }

    // Generate a prefix label, if required.
    if ($this->myLabelPosition=='pre')
    {
      $ret = $this->generateLabel();
    }
    else
    {
      $ret = '';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function validateBase(&$theInvalidFormControls)
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate($this);
      if ($valid!==true)
      {
        $theInvalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
