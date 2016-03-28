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

    /** @var SelectControl $input */
    $input = $this->myForm->createFormControl('select', 'wrd_id', 'Menu Title');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setOptionsObfuscator(Abc::getObfuscator('wrd'));
    $input->setEmptyOption('');


    // Create text box for the title the menu item.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'mnu_title', 'Menu Title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);


    // Create select box for chose page for menu.
    $pages = Abc::$DL->systemPageGetAll($this->myLanId);
    /** @var SelectControl $input */
    $input = $this->myForm->createFormControl('select', 'pag_id', 'Page Class', true);
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setEmptyOption(' ');
    $input->setOptionsObfuscator(Abc::getObfuscator('pag'));


    // Create text form control for input menu level.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'mnu_level', 'Menu Level', true);
    $input->setAttrMaxLength(C::LEN_MNU_LEVEL);
    $input->setValue(1);
    $input->addValidator(new IntegerValidator(0, 100));


    // Create text form control for input menu group.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'mnu_group', 'Menu Group', true);
    $input->setAttrMaxLength(C::LEN_MNU_GROUP);
    $input->addValidator(new IntegerValidator(0, 100));


    // Create text form control for input menu weight.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'mnu_weight', 'Menu Weight', true);
    $input->setAttrMaxLength(C::LEN_MNU_WEIGHT);
    $input->addValidator(new IntegerValidator(0, 999));


    // Create text box for URL of the menu item.
    /** @var TextControl $input */
    $input = $this->myForm->createFormControl('text', 'mnu_link', 'Menu Link');
    $input->setAttrMaxLength(C::LEN_MNU_LINK);

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
