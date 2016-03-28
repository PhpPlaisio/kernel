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
 * Abstract parent page for inserting or modifying a page.
 */
abstract class PageBasePage extends CorePage
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
   * The ID of the page created or modified
   *
   * @var int .
   */
  protected $myTargetPagId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a functionality.
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
    $this->myForm = new CoreForm();

    // Create select box for (known) page titles.
    $titles = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_PAGE_TITLE, $this->myLanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setEmptyOption(true);
    $input->setOptionsObfuscator(Abc::getObfuscator('wrd'));
    $this->myForm->addFormControl($input, 'Title');

    // Create text box for (new) page title.
    $input = new TextControl('pag_title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->myForm->addFormControl($input, 'Title');
    /** @todo Add validator: either wrd_id is not empty or pag_title is not empty */


    // Create form control for page tab group.
    $tabs  = Abc::$DL->systemTabGetAll($this->myLanId);
    $input = new SelectControl('ptb_id');
    $input->setOptions($tabs, 'ptb_id', 'ptb_label');
    $input->setEmptyOption('ptb');
    $this->myForm->addFormControl($input, 'Page Tab');


    // Create form control for original page.
    $pages = Abc::$DL->systemPageGetAllMasters($this->myLanId);
    $input = new SelectControl('pag_id_org');
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setEmptyOption('');
    $input->setOptionsObfuscator(Abc::getObfuscator('pag'));
    $this->myForm->addFormControl($input, 'Original Page');


    // Create form control for menu item with which the page is associated..
    $menus = Abc::$DL->systemMenuGetAllEntries($this->myLanId);
    $input = new SelectControl('mnu_id');
    $input->setOptions($menus, 'mnu_id', 'mnu_name');
    $input->setEmptyOption(true);
    $input->setOptionsObfuscator(Abc::getObfuscator('mnu'));
    $this->myForm->addFormControl($input, 'Menu');


    // Create form control for page alias.
    $input = new TextControl('pag_alias');
    $input->setAttrMaxLength(C::LEN_PAG_ALIAS);
    $this->myForm->addFormControl($input, 'Alias');


    // Create form control for page class.
    $input = new TextControl('pag_class');
    $input->setAttrMaxLength(C::LEN_PAG_CLASS);
    $this->myForm->addFormControl($input, 'Class', true);


    // Create form control for the page label.
    $input = new TextControl('pag_label');
    $input->setAttrMaxLength(C::LEN_PAG_LABEL);
    $this->myForm->addFormControl($input, 'Label');


    // Create form control for the weight of the page (inside a tab group).
    /** @todo validate weight is a number and/or form control or validator for numeric input. */
    $input = new TextControl('pag_weight');
    $input->setAttrMaxLength(C::LEN_PAG_WEIGHT);
    $this->myForm->addFormControl($input, 'Weight');
    

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
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->databaseAction();

    Http::redirect(PageDetailsPage::getUrl($this->myTargetPagId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
