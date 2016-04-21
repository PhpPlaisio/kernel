<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

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

    // Input for module.
    $input = new SelectControl('mdl_id');
    $input->setOptions($modules, 'mdl_id', 'mdl_name');
    $input->setEmptyOption();
    $this->myForm->addFormControl($input, 'Module', true);

    // Input for functionality name.
    // @todo Make control for reusing a word or create a new word. 
    $input = new SelectControl('wrd_id');
    $input->setOptions($words, 'wrd_id', 'wrd_text');
    $input->setEmptyOption();
    $this->myForm->addFormControl($input, 'Name');

    $input = new TextControl('fun_name');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->myForm->addFormControl($input, 'Name');

    // Create a submit button.
    $this->myForm->addSubmitButton($this->myButtonWrdId, 'handleForm');
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

    HttpHeader::redirectSeeOther(FunctionalityOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
