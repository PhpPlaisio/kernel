<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\Control;

use SetBased\Abc\Babel;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\ResetControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Fieldset for visible form controls in core form.
 */
class CoreFieldSet extends FieldSet
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The complex form control holding the buttons of this fieldset.
   *
   * @var CoreButtonControl
   */
  private $myButtonFormControl;

  /**
   * The title of the in the header of the form of this field set.
   *
   * @var string
   */
  private $myHtmlTitle;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a button control to this fieldset.
   *
   * @param string $theSubmitButtonText The text of the submit button.
   * @param null   $theResetButtonText  The text of the reset button. If null no reset button will be created.
   * @param string $theSubmitName       The name of the submit button.
   * @param string $theResetName        The name of the reset button.
   *
   * @return CoreButtonControl
   */
  public function addButton($theSubmitButtonText = 'OK',
                            $theResetButtonText = null,
                            $theSubmitName = 'submit',
                            $theResetName = 'reset'
  )
  {
    $this->myButtonFormControl = new CoreButtonControl('');

    // Create submit button.
    $submit = new SubmitControl($theSubmitName);
    $submit->setValue($theSubmitButtonText);
    $this->myButtonFormControl->addFormControl($submit);

    // Create reset button.
    if ($theResetButtonText)
    {
      $reset = new ResetControl($theResetName);
      $reset->setValue($theResetButtonText);
      $this->myButtonFormControl->addFormControl($reset);
    }

    $this->addFormControl($this->myButtonFormControl);

    return $this->myButtonFormControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a submit button to this fieldset.
   *
   * @param int|string $theWrdId Depending on the type:
   *                             <ul>
   *                             <li>int: The ID of the word of the button text.
   *                             <li>string: The text of the button.
   *                             </ul>
   * @param string     $theName  The name of the submit button.
   *
   * @return SubmitControl
   */
  public function addSubmitButton($theWrdId, $theName = 'submit')
  {
    // If necessary create a button form control.
    if (!$this->myButtonFormControl)
    {
      $this->myButtonFormControl = new CoreButtonControl('');
      $this->addFormControl($this->myButtonFormControl);
    }

    $input = new SubmitControl($theName);
    $input->setValue((is_int($theWrdId)) ? Babel::getWord($theWrdId) : $theWrdId);
    $this->myButtonFormControl->addFormControl($input);

    return $input;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $ret = $this->generateStartTag();

    $ret .= '<div class="input_table">';
    $ret .= '<table>';

    if ($this->myHtmlTitle)
    {
      $ret .= '<thead>';
      $ret .= '<tr>';
      $ret .= '<th colspan="2">'.$this->myHtmlTitle.'</th>';
      $ret .= '</tr>';
      $ret .= '</thead>';
    }

    if ($this->myButtonFormControl)
    {
      $ret .= '<tfoot class="button">';
      $ret .= '<tr>';
      $ret .= '<td colspan="2">';
      $ret .= $this->myButtonFormControl->generate();
      $ret .= '</td>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '<tbody>';
    foreach ($this->myControls as $control)
    {
      if ($control!=$this->myButtonFormControl)
      {
        $ret .= '<tr>';
        $ret .= '<th>';
        $ret .= Html::txt2Html($control->getAttribute('_abc_label'));
        $mandatory = $control->getAttribute('_abc_mandatory');
        if (!empty($mandatory)) $ret .= '<span class="mandatory">*</span>';
        $ret .= '</th>';

        $ret .= '<td>';
        $ret .= $control->generate();
        $ret .= '</td>';

        $messages = $control->getErrorMessages(true);
        if ($messages)
        {
          $ret .= '<td class="error">';
          $first = true;
          foreach ($messages as $err)
          {
            if ($first) $first = false;
            else        $ret .= '<br/>';
            $ret .= Html::txt2Html($err);
          }
          $ret .= '</td>';
        }

        $ret .= '</tr>';
      }
    }

    $ret .= '</tbody>';
    $ret .= '</table>';
    $ret .= '</div>';

    $ret .= $this->generateEndTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of the form of this field set.
   *
   * @param string $theTitle
   */
  public function setTitle($theTitle)
  {
    $this->myHtmlTitle = Html::txt2Html($theTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
