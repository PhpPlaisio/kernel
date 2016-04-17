<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * parent class for form controls submit, reset, and button.
 */
class PushMeControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /** The type of this button. Valid values are:
   *  <ul>
   *  <li> submit
   *  <li> reset
   *  <li> button
   *  </ul>
   */
  protected $myButtonType;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $this->attributes['type'] = $this->myButtonType;
    $this->attributes['name'] = $this->mySubmitName;

    if ($this->myFormatter) $this->attributes['value'] = $this->myFormatter->format($this->myValue);
    else                    $this->attributes['value'] = $this->myValue;

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not retrieved by this method.
   *
   * @param array $theValues Not used.
   */
  public function getCurrentValues(&$theValues)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $theValues Not used.
   */
  public function mergeValuesBase($theValues)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $theValues Not used.
   */
  public function setValuesBase($theValues)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if (isset($theSubmittedValue[$submit_name]) && $theSubmittedValue[$submit_name]===$this->myValue)
    {
      // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
      // So, skip the following code.
      // $theChangedInputs[$this->myName] = $this;

      $theWhiteListValue[$this->myName] = $this->myValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
