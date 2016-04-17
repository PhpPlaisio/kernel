<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A pseudo form control for generating table rows in a Louver control.
 */
class SlatControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var Control;
   */
  private $myDeleteControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    // Create start tag of table row.
    $ret = Html::generateTag('tr', $this->attributes);

    // Create table cells.
    foreach ($this->myControls as $control)
    {
      $ret .= $control->getHtmlTableCell();
    }

    // Create table cell with error message, if any.
    $ret .= $this->generateErrorCell();

    // Create end tag of table row.
    $ret .= '</tr>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setDeleteControl($theControl)
  {
    $this->myDeleteControl = $theControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes a validators on the child form controls of this form complex control. If  and only if all child form
   * controls are valid the validators of this complex control are executed.
   *
   * @param array $theInvalidFormControls A nested array of invalid form controls.
   *
   * @return bool True if and only if all form controls are valid.
   */
  public function validateBase(&$theInvalidFormControls)
  {
    $valid = true;

    if ($this->myDeleteControl)
    {
      if (!$this->myDeleteControl->validateBase($theInvalidFormControls))
      {
        $this->myInvalidControls[] = $this->myDeleteControl;
        $valid                     = false;
      }
      else
      {
        if ($this->myDeleteControl->getSubmittedValue())
        {
          return $valid;
        }
      }
    }

    // First, validate all child form controls.
    foreach ($this->myControls as $control)
    {
      if ($control!==$this->myDeleteControl)
      {
        if (!$control->validateBase($theInvalidFormControls))
        {
          $this->myInvalidControls[] = $control;
          $valid                     = false;
        }
      }
    }

    if ($valid)
    {
      // All the child form controls are valid. Validate this complex form control.
      foreach ($this->myValidators as $validator)
      {
        $valid = $validator->validate($this);
        if ($valid!==true)
        {
          $theInvalidFormControls[] = $this;
          break;
        }
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a table cell with the errors messages of all form controls at this row.
   *
   * @return string
   */
  protected function generateErrorCell()
  {
    $ret = '';

    if (!$this->isValid())
    {
      $error_messages = $this->getErrorMessages(true);

      $ret .= '<td class="error">';
      if (!empty($error_messages))
      {
        foreach ($error_messages as $message)
        {
          $ret .= Html::txt2Html($message);
          $ret .= '<br/>';
        }
      }
      $ret .= '</td>';
    }
    else
    {
      $ret .= '<td class="error"></td>';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
