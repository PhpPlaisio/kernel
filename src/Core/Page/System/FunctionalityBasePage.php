<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting and updating a functionality.
 */
abstract class FunctionalityBasePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word for the text of the submit button of the form shown on this page.
   *
   * @var int
   */
  protected $myButtonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a functionality.
   *
   * @return null
   */
  abstract protected function dataBaseAction();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $this->loadValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form.
   *
   * @return null
   */
  abstract protected function loadValues();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $modules = Abc::$DL->systemModuleGetAll($this->myLanId);
    $words   = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_FUNCTIONALITIES, $this->myLanId);

    $this->myForm = new CoreForm();

    $control = $this->myForm->createFormControl('select', 'mdl_id', 'Module', true);
    $control->setOptions($modules, 'mdl_id', 'mdl_name');
    $control->setEmptyOption(' ');

    $control = $this->myForm->createFormControl('select', 'wrd_id', 'Name');
    $control->setOptions($words, 'wrd_id', 'wrd_text');
    $control->setEmptyOption(' ');

    $control = $this->myForm->createFormControl('text', 'fun_name', 'Name');
    $control->setAttrMaxLength(C::LEN_WDT_TEXT);

    // Create a submit button.
    $this->myForm->addSubmitButton($this->myButtonWrdId, 'handleForm');

    // $this->myForm->addFormValidator( new SystemFunctionalityInsertFormValidator() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->myForm->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->myForm->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->dataBaseAction();

    Http::redirect(FunctionalityOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
