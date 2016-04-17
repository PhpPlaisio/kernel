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
   * The maximum size of a text control. The maximum text length can be larger.
   *
   * @var int
   */
  public static $ourMaxTextSize = 80;

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

    $this->attributes['class']        = 'input_table';
    $this->attributes['autocomplete'] = false;

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
    switch (true)
    {
      // Add hidden, constant, and invisible controls to the fieldset for hidden controls.
      case ($theControl instanceof HiddenControl):
      case ($theControl instanceof ConstantControl):
      case ($theControl instanceof InvisibleControl):
        $this->myHiddenFieldSet->addFormControl($theControl);
        break;

      // Add all other controls to the visible fieldset.
      default:
        switch (true)
        {
          // Set the size of text controls.
          case ($theControl instanceof TextControl):
            $max_length = $theControl->getAttribute('maxlength');
            $size       = (isset($max_length)) ? min($max_length, self::$ourMaxTextSize) : self::$ourMaxTextSize;
            $theControl->setAttrSize($size);
            break;
        }

        $this->myVisibleFieldSet->addFormControl($theControl);

        if (isset($theWrdId))
        {
          $theControl->setFakeAttribute('_abc_label', (is_int($theWrdId)) ? Babel::getWord($theWrdId) : $theWrdId);
        }

        if ($theMandatoryFlag)
        {
          $theControl->addValidator(new MandatoryValidator(0));
          $theControl->setFakeAttribute('_abc_mandatory', true);
        }
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
   */
  public function addSubmitButton($theWrdId, $theMethod, $theName = 'submit')
  {
    $control = $this->myVisibleFieldSet->addSubmitButton($theWrdId, $theName);
    $this->addSubmitHandler($control, $theMethod);
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
