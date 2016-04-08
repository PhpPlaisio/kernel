<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\HtmlControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a company specific page that overrides a standard page.
 */
class SpecificPageUpdatePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  /**
   * The ID of the target page.
   *
   * @var int
   */
  private $myTargetPagId;

  /**
   * The details om the company specific page.
   *
   * @var array
   */
  private $myTargetPageDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myTargetPagId = self::getCgiId('tar_pag', 'pag');

    $this->myTargetPageDetails = Abc::$DL->companySpecificPageGetDetails($this->myActCmpId,
                                                                         $this->myTargetPagId,
                                                                         $this->myLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $theCmpId       The ID of the target company.
   * @param int $theTargetPagId The ID of the page.
   *
   * @return string The URL of this page.
   */
  public static function getUrl($theCmpId, $theTargetPagId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_UPDATE, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');
    $url .= self::putCgiVar('tar_pag', $theTargetPagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the Company specific page after a form submit.
   */
  protected function databaseAction()
  {
    if (!$this->myForm->getChangedControls()) return;

    $values = $this->myForm->getValues();

    Abc::$DL->companySpecificPageUpdate($this->myActCmpId, $this->myTargetPagId, $values['pag_class_child']);
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
    $this->myForm = new CoreForm();

    // Show the ID of the page.
    $input = new HtmlControl('pag_id');
    $input->setHtml($this->myTargetPageDetails['pag_id']);
    $this->myForm->addFormControl($input, 'ID');

    // Show the title of the page.
    $input = new HtmlControl('pag_title');
    $input->setHtml($this->myTargetPageDetails['pag_title']);
    $this->myForm->addFormControl($input, 'Title');

    // Show the parent class name of the page.
    $input = new HtmlControl('pag_class_parent');
    $input->setHtml($this->myTargetPageDetails['pag_class_parent']);
    $this->myForm->addFormControl($input, 'Parent Class');

    // Create text control for the child class name.
    $input = new TextControl('pag_class_child');
    $input->setValue($this->myTargetPageDetails['pag_class_child']);
    $this->myForm->addFormControl($input, 'Child Class');

    // Create a submit button.
    $this->myForm->addSubmitButton(C::WRD_ID_BUTTON_UPDATE, 'handleForm');
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

    Http::redirect(SpecificPageOverviewPage::getUrl($this->myActCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
