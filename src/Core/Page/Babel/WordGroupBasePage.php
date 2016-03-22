<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting and updating a word group.
 */
abstract class WordGroupBasePage extends BabelPage
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
   * The ID of the word group.
   *
   * @var int
   */
  protected $myWdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by a child page to actually insert or update a word group.
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
    $this->setValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial values of the form shown on this page.
   *
   * @return null
   */
  abstract protected function setValues();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->myForm = new CoreForm();

    // Show word group ID (update only).
    if ($this->myWdgId)
    {
      $input = $this->myForm->createFormControl('span', 'wdg_id', 'ID');
      $input->setInnerText($this->myWdgId);
    }

    // Input for the name of the word group.
    $input = $this->myForm->createFormControl('text', 'wdg_name', 'Name', true);
    $input->setAttrMaxLength(C::LEN_WDG_NAME);

    // Input for the label of the word group.
    $input = $this->myForm->createFormControl('text', 'wdg_label', 'Label');
    $input->setAttrMaxLength(C::LEN_WRD_LABEL);

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

    Http::redirect(WordGroupDetailsPage::getUrl($this->myWdgId, $this->myActLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

