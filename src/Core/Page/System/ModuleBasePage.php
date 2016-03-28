<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\FormValidator\SystemModuleInsertCompoundValidator;
use SetBased\Abc\Core\Page\CorePage;

use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for inserting or updating the details of a module.
 */
abstract class ModuleBasePage extends CorePage
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

  /**
   * @var int The ID of de module to be updated or inserted.
   */
  protected $myMdlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a module.
   *
   * @return null
   */
  abstract protected function databaseAction();

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
    $words = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_MODULE, $this->myLanId);

    $this->myForm = new CoreForm();

    if ($words)
    {
      // If there are unused modules names (i.e. words in the word group WDG_ID_MODULES that are not used by a
      // module) create a select box with free modules names.
      /** @var SelectControl $input */
      $input = $this->myForm->createFormControl('select', 'wrd_id', 'Module Name');
      $input->setOptions($words, 'wrd_id', 'wrd_text');
      $input->setEmptyOption(' ');
    }

    // Create a text box for (new) module name.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'mdl_name', 'Module Name');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);

    // Create a submit button.
    $this->myForm->addSubmitButton($this->myButtonWrdId, 'handleForm');

    $this->myForm->addValidator(new SystemModuleInsertCompoundValidator());
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
      case  'handleForm':
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
    $this->databaseAction();

    Http::redirect(ModuleDetailsPage::getUrl($this->myMdlId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
