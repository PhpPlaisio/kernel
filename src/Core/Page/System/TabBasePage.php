<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;

use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting or modifying a page group.
 */
abstract class TabBasePage extends CorePage
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
   * Must implemented by child pages to actually insert or update a page group.
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
   * Handles the form submit.
   */
  protected function handleForm()
  {
    $this->databaseAction();

    Http::redirect(TabOverviewPage::getUrl());
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
    $this->myForm = new CoreForm();

    // Create select box for (known) page titles.
    $titles = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_PAGE_GROUP_TITLE, $this->myLanId);

    /** @var SelectControl $input */
    $input = $this->myForm->createFormControl('select', 'wrd_id', 'Title');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setEmptyOption(true);

    // Create text box for (new) page title.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'ptb_title', 'Title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);

    // Create form control for the page label.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'ptb_label', 'Label');
    $input->setAttrMaxLength(C::LEN_PTB_LABEL);

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
      case  'handleForm':
        $this->handleForm();
        break;
      
      default:
        $this->myForm->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
