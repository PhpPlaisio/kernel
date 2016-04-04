<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Validator\IntegerValidator;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for inserting or updating a menu entry.
 */
abstract class MenuBasePage extends CorePage
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
   * Must implemented by child pages to actually insert or update a menu entry.
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
    $titles = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_MENU, $this->myLanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setOptionsObfuscator(Abc::getObfuscator('wrd'));
    $input->setEmptyOption();
    $this->myForm->addFormControl($input, 'Menu Title');

    // Create text box for the title the menu item.
    $input = new TextControl('mnu_title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->myForm->addFormControl($input, 'Menu Title');


    // Create select box for chose page for menu.
    $pages = Abc::$DL->systemPageGetAll($this->myLanId);
    $input = new SelectControl('pag_id');
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Abc::getObfuscator('pag'));
    $this->myForm->addFormControl($input, 'Page Class', true);


    // Create text form control for input menu level.
    $input = new TextControl('mnu_level');
    $input->setAttrMaxLength(C::LEN_MNU_LEVEL);
    $input->setValue(1);
    $input->addValidator(new IntegerValidator(0, 100));
    $this->myForm->addFormControl($input, 'Menu Level', true);


    // Create text form control for input menu group.
    $input = new TextControl('mnu_group');
    $input->setAttrMaxLength(C::LEN_MNU_GROUP);
    $input->addValidator(new IntegerValidator(0, 100));
    $this->myForm->addFormControl($input, 'Menu Group', true);


    // Create text form control for input menu weight.
    $input = new TextControl('mnu_weight');
    $input->setAttrMaxLength(C::LEN_MNU_WEIGHT);
    $input->addValidator(new IntegerValidator(0, 999));
    $this->myForm->addFormControl($input, 'Menu Weight', true);


    // Create text box for URL of the menu item.
    $input = new TextControl('mnu_link');
    $input->setAttrMaxLength(C::LEN_MNU_LINK);
    $this->myForm->addFormControl($input, 'Menu Link');
    

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
    $this->databaseAction();

    Http::redirect(MenuOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
