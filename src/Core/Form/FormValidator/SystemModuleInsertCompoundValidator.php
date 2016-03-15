<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\FormValidator;

use SetBased\Abc\Form\Validator\CompoundValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Form validator for the form for inserting or updating a module.
 */
class SystemModuleInsertCompoundValidator implements CompoundValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function validate($theControl)
  {
    $ret = true;

    $values = $theControl->getSubmittedValue();

    // Only and only one of mdl_name or wrd_id must be set.
    if (!isset($values['mdl_name']) && !$values['wrd_id'])
    {
      $control = $theControl->getFormControlByName('wrd_id');
      $control->setErrorMessage('Mandatory field');

      $control = $theControl->getFormControlByName('mdl_name');
      $control->setErrorMessage('Mandatory field');

      $ret = false;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
