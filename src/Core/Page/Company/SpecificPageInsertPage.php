<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
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
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_SPECIFIC_PAGE_INSERT, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');

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
    $method = $this->myForm->execute();
    if ($method) $this->$method();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $pages = Abc::$DL->systemPageGetAll($this->myLanId);

    $this->myForm = new CoreForm();

    $control = $this->myForm->createFormControl('select', 'prt_pag_id', 'Parent Class');
    $control->setOptions($pages, 'pag_id', 'pag_class');
    $control->setOptionsObfuscator(Abc::getObfuscator('pag'));

    $this->myForm->createFormControl('text', 'pag_class_child', 'Child Class');

    // Create a submit button.
    $this->myForm->addSubmitButton(C::WRD_ID_BUTTON_INSERT, 'handleForm');
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
