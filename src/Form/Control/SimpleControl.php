<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Formatter\Formatter;
use SetBased\Abc\Helper\Html;
use SetBased\Exception\LogicException;

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
  protected $cleaner;

  /**
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var Formatter
   */
  protected $formatter;

  /**
   * The label of this form control.
   *
   * @var string A HTML snippet.
   */
  protected $label;

  /**
   * The attributes for the label of this form control.
   *
   * @var string[]
   */
  protected $labelAttributes = [];

  /**
   * The position of the label of this form control.
   * <ul>
   * <li> 'pre'  The label will be inserted before the HML code of this form control.
   * <li> 'post' The label will be appended after the HML code of this form control.
   * </ul>
   *
   * @var string|null
   */
  protected $labelPosition;

  /**
   * The value of this form control.
   *
   * @var string
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $name The name of the form control.
   */
  public function __construct($name)
  {
    parent::__construct($name);

    // A simple form control must have a name.
    if ($this->name==='')
    {
      throw new LogicException('Name is empty');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of this form control to the values.
   *
   * @param array $values
   */
  public function getSetValuesBase(&$values)
  {
    $values[$this->name] = $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return string
   */
  public function getSubmittedValue()
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $values
   */
  public function mergeValuesBase($values)
  {
    if (array_key_exists($this->name, $values))
    {
      $this->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_input_autocomplete.asp).
   * * Any value that evaluates to true will set the attribute to 'on'.
   * * Any value that evaluates to false will set the attribute to 'off'.
   * * Null will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrAutoComplete($value)
  {
    $this->attributes['autocomplete'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autofocus](http://www.w3schools.com/tags/att_input_autofocus.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'autofocus'. Any other value will unset the attribute.
   *
   * @param string $value The attribute value.
   */
  public function setAttrAutoFocus($value)
  {
    $this->attributes['autofocus'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [disabled](http://www.w3schools.com/tags/att_input_disabled.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'disabled'. Any other value will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrDisabled($value)
  {
    $this->attributes['disabled'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [form](http://www.w3schools.com/tags/att_input_form.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrForm($value)
  {
    $this->attributes['form'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [list](http://www.w3schools.com/tags/att_input_list.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrList($value)
  {
    $this->attributes['list'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [max](http://www.w3schools.com/tags/att_input_max.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrMax($value)
  {
    $this->attributes['max'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [maxlength](http://www.w3schools.com/tags/att_input_maxlength.asp).
   *
   * @param int $value The attribute value.
   */
  public function setAttrMaxLength($value)
  {
    $this->attributes['maxlength'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [min](http://www.w3schools.com/tags/att_input_min.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrMin($value)
  {
    $this->attributes['min'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [multiple](http://www.w3schools.com/tags/att_input_multiple.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'multiple'. Any other value will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrMultiple($value)
  {
    $this->attributes['multiple'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [pattern](http://www.w3schools.com/tags/att_input_pattern.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrPattern($value)
  {
    $this->attributes['pattern'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [placeholder](http://www.w3schools.com/tags/att_input_placeholder.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrPlaceHolder($value)
  {
    $this->attributes['placeholder'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [readonly](http://www.w3schools.com/tags/att_input_readonly.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'readonly'. Any other value will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrReadOnly($value)
  {
    $this->attributes['readonly'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [required](http://www.w3schools.com/tags/att_input_required.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'required'. Any other value will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrRequired($value)
  {
    $this->attributes['required'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [size](http://www.w3schools.com/tags/att_input_size.asp).
   *
   * @param int $value The attribute value.
   */
  public function setAttrSize($value)
  {
    $this->attributes['size'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [step](http://www.w3schools.com/tags/att_input_step.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrStep($value)
  {
    $this->attributes['step'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner $cleaner The cleaner.
   */
  public function setCleaner($cleaner)
  {
    $this->cleaner = $cleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the formatter for this form control.
   *
   * @param Formatter $formatter The formatter.
   */
  public function setFormatter($formatter)
  {
    $this->formatter = $formatter;
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
   * @param string $name  The name of the attribute.
   * @param string $value The value for the attribute.
   */
  public function setLabelAttribute($name, $value)
  {
    if ($value==='' || $value===null || $value===false)
    {
      unset($this->labelAttributes[$name]);
    }
    else
    {
      if ($name=='class' && isset($this->labelAttributes[$name]))
      {
        $this->labelAttributes[$name] .= ' ';
        $this->labelAttributes[$name] .= $value;
      }
      else
      {
        $this->labelAttributes[$name] = $value;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the label for this form control.
   *
   * @param string $htmlSnippet    The (inner) label HTML snippet. It is the developer's responsibility that it is
   *                               valid HTML code.
   */
  public function setLabelHtml($htmlSnippet)
  {
    $this->label = $htmlSnippet;
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
   * @param string|null $position
   */
  public function setLabelPosition($position)
  {
    $this->labelPosition = $position;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the abel for this form control.
   *
   * @param string $text The (inner) label text. Special characters are converted to HTML entities.
   */
  public function setLabelText($text)
  {
    $this->label = Html::txt2Html($text);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string $value The new value for the form control.
   */
  public function setValue($value)
  {
    $this->value = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function setValuesBase($values)
  {
    $this->setValue(isset($values[$this->name]) ? $values[$this->name] : null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for the label for this form control.
   *
   * @return string
   */
  protected function generateLabel()
  {
    return Html::generateElement('label', $this->labelAttributes, $this->label, true);
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
    if ($this->labelPosition=='post')
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
    if (isset($this->labelPosition))
    {
      if (!isset($this->attributes['id']))
      {
        $id                           = Html::getAutoId();
        $this->attributes['id']       = $id;
        $this->labelAttributes['for'] = $id;
      }
      else
      {
        $this->labelAttributes['for'] = $this->attributes['id'];
      }
    }

    // Generate a prefix label, if required.
    if ($this->labelPosition=='pre')
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
  protected function validateBase(&$invalidFormControls)
  {
    $valid = true;

    foreach ($this->validators as $validator)
    {
      $valid = $validator->validate($this);
      if ($valid!==true)
      {
        $invalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
