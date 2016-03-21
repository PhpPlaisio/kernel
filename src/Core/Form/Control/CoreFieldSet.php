<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\Control;

use SetBased\Abc\Babel;
use SetBased\Abc\Core\Form\Validator\MandatoryValidator;
use SetBased\Abc\Form\Control\CheckboxesControl;
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\RadiosControl;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Form\Control\TextControl;
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

    $submit = $this->myButtonFormControl->createFormControl('submit', $theSubmitName);
    $submit->setValue($theSubmitButtonText);

    if ($theResetButtonText)
    {
      $reset = $this->myButtonFormControl->createFormControl('reset', $theResetName);
      $reset->setValue($theResetButtonText);
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
      $this->myButtonFormControl = $this->addFormControl(new CoreButtonControl(''));
    }

    /** @var SubmitControl $input */
    $input = $this->myButtonFormControl->addFormControl(new SubmitControl($theName));
    $input->setValue((is_int($theWrdId)) ? Babel::getWord($theWrdId) : $theWrdId);

    return $input;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form control and adds this form control to this fieldset.
   *
   * @param string          $theType          The type of the form control.
   * @param string          $theName          The name of the form control.
   * @param int|string|null $theWrdId         Depending on the type:
   *                                          <ul>
   *                                          <li>int:    The wrd_id of the legend of the form control.
   *                                          <li>string: The legend of the form control.
   *                                          <li>null:   The form control has no legend.
   *                                          </ul>
   * @param bool            $theMandatoryFlag If set the form control is mandatory.
   *
   * @return CheckboxesControl|ComplexControl|RadiosControl|SelectControl|SimpleControl|SpanControl
   */
  public function createFormControl($theType, $theName, $theWrdId = null, $theMandatoryFlag = false)
  {
    switch ($theType)
    {
      case 'text':
        $control = new TextControl($theName);
        $control->setAttrSize(80);
        $ret = $this->addFormControl($control);
        break;

      default:
        $ret = parent::createFormControl($theType, $theName);
    }

    if ($theWrdId)
    {
      if (is_int($theWrdId))
      {
        $ret->setFakeAttribute('_abc_label', Babel::getWord($theWrdId));
      }
      else
      {
        $ret->setFakeAttribute('_abc_label', $theWrdId);
      }
    }

    if ($theMandatoryFlag)
    {
      $ret->addValidator(new MandatoryValidator(0, 0));
      $ret->setFakeAttribute('_set_mandatory', true);
    }

    return $ret;
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
        if ($control->getAttribute('_set_mandatory')) $ret .= '<span class="mandatory">*</span>';
        $ret .= '</th>';

        $ret .= '<td>';
        $ret .= $control->generate();
        $ret .= '</td>';

        $errmsg = $control->getErrorMessages(true);
        if ($errmsg)
        {
          $ret .= '<td class="error">';
          $first = true;
          foreach ($errmsg as $err)
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
