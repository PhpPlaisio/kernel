<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a company specific page that overrides a standard page.
 */
class SpecificPageInsertPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $theCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($theCmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_INSERT, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a company specific page.
   */
  protected function databaseAction()
  {
    $values = $this->myForm->getValues();

    Abc::$DL->companySpecificPageInsert($this->myActCmpId, $values['prt_pag_id'], $values['pag_class_child']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $pages = Abc::$DL->systemPageGetAll($this->myLanId);

    $this->myForm = new CoreForm();

    // Input for parent class.
    $input = new SelectControl('prt_pag_id');
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setOptionsObfuscator(Abc::getObfuscator('pag'));
    $this->myForm->addFormControl($input, 'Parent Class');

    // Input for company specific page.
    $input = new TextControl('pag_class_child');
    $input->setAttrMax(C::LEN_PAG_CLASS);
    $this->myForm->addFormControl($input, 'Child Class');

    // Create a submit button.
    $this->myForm->addSubmitButton(C::WRD_ID_BUTTON_INSERT, 'handleForm');
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

    Http::redirect(SpecificPageOverviewPage::getUrl($this->myActCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
