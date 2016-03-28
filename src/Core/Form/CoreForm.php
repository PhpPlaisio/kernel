<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form;

use SetBased\Abc\Babel;
use SetBased\Abc\Core\Form\Control\CoreFieldSet;
use SetBased\Abc\Core\Form\Validator\MandatoryValidator;
use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\HiddenControl;
use SetBased\Abc\Form\Control\InvisibleControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Form class for all forms in the core of ABC.
 */
class CoreForm extends Form
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * FieldSet for all form control elements not of type "hidden".
   *
   * @var CoreFieldSet
   */
  protected $myVisibleFieldSet;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($theName = '', $theCsrfCheckFlag = true)
  {
    parent::__construct($theName, $theCsrfCheckFlag);

    $this->myAttributes['class']        = 'input_table';
    $this->myAttributes['autocomplete'] = false;

    $this->myVisibleFieldSet = $this->addFieldSet(new CoreFieldSet(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to thi form.
   *
   * @param Control         $theControl       The from control
   * @param int|string|null $theWrdId         Depending on the type:
   *                                          <ul>
   *                                          <li>int:    The wrd_id of the legend of the form control.
   *                                          <li>string: The legend of the form control.
   *                                          <li>null:   The form control has no legend.
   *                                          </ul>
   * @param bool            $theMandatoryFlag If true the form control is mandatory.
   */
  public function addFormControl($theControl, $theWrdId = null, $theMandatoryFlag = false)
  {
    if ($theControl instanceof HiddenControl ||
      $theControl instanceof ConstantControl ||
      $theControl instanceof InvisibleControl
    )
    {
      $this->myHiddenFieldSet->addFormControl($theControl);
    }
    else
    {
      $this->myVisibleFieldSet->addFormControl($theControl);

      if ($theControl instanceof TextControl)
      {
        $theControl->setAttrSize(80);
      }
    }

    if ($theWrdId)
    {
      if (is_int($theWrdId))
      {
        $theControl->setFakeAttribute('_abc_label', Babel::getWord($theWrdId));
      }
      else
      {
        $theControl->setFakeAttribute('_abc_label', $theWrdId);
      }
    }

    if ($theMandatoryFlag)
    {
      $theControl->addValidator(new MandatoryValidator(0));
      $theControl->setFakeAttribute('_set_mandatory', true);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a submit button to this form.
   *
   * @param int|string $theWrdId  Depending on the type:
   *                              <ul>
   *                              <li>int:    The ID of the word of the button text.
   *                              <li>string: The text of the button.
   *                              </ul>
   * @param string     $theMethod The name of method for handling the form submit.
   * @param string     $theName   The name of the submit button.
   *
   * @return SubmitControl
   */
  public function addSubmitButton($theWrdId, $theMethod, $theName = 'submit')
  {
    /** @var SubmitControl $control */
    $control = $this->myVisibleFieldSet->addSubmitButton($theWrdId, $theName);
    $this->addSubmitHandler($control, $theMethod);

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form control.
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
   * @return Control
   */
  public function createFormControl($theType, $theName, $theWrdId = null, $theMandatoryFlag = false)
  {
    switch ($theType)
    {
      case 'hidden':
      case 'constant':
      case 'invisible':
        $ret = $this->myHiddenFieldSet->createFormControl($theType, $theName);
        break;

      default:
        // Add all other controls to the visible field set.
        $ret = $this->myVisibleFieldSet->createFormControl($theType, $theName, $theWrdId, $theMandatoryFlag);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the visible fieldset of this form.
   *
   * @return CoreFieldSet
   */
  public function getVisibleFieldSet()
  {
    return $this->myVisibleFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param int $theWrdId The wrd_id of the title.
   */
  public function setTitle($theWrdId)
  {
    $this->myVisibleFieldSet->setTitle(Babel::getWord($theWrdId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param string $theTitle The title.
   */
  public function setTitleText($theTitle)
  {
    $this->myVisibleFieldSet->setTitle($theTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
