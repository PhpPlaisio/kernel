<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for pages about companies.
 */
abstract class CompanyPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the company of which data is shown on this page (i.e. the target company).
   *
   * @var int
   */
  protected $myActCmpId;

  /**
   * The details of the company of which data is shown on this page.
   *
   * @var array
   */
  protected $myCompanyDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myActCmpId = self::getCgiId('cmp', 'cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL to a child page of this page.
   *
   * @param int $thePagId The ID of the child page.
   * @param int $theCmpId The ID of the target company.
   *
   * @return string The URL.
   */
  public static function getChildUrl($thePagId, $theCmpId)
  {
    $url = self::putCgiVar('pag', $thePagId, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows brief information about the target company.
   */
  protected function echoDashboard()
  {
    // Return immediately if the cmp_id is not set.
    if (!$this->myActCmpId) return;

    $this->myCompanyDetails = Abc::$DL->companyGetDetails($this->myActCmpId);

    echo '<div id="dashboard">';
    echo '<div id="info">';

    echo '<div id="info0">';
    echo Html::txt2Html($this->myCompanyDetails['cmp_abbr']);
    echo '<br/>';
    echo '<br/>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    if ($this->myActCmpId)
    {
      $this->appendPageTitle($this->myCompanyDetails['cmp_abbr']);
    }
    else
    {
      $this->getCompany();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getTabUrl($thePagId)
  {
    if ($this->myActCmpId || $thePagId==C::PAG_ID_COMPANY_OVERVIEW)
    {
      return self::getChildUrl($thePagId, $this->myActCmpId);
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handle the form submit of the form for selecting a company.
   *
   * @param CoreForm $theForm The form.
   */
  protected function handleCompanyForm($theForm)
  {
    $abc = Abc::getInstance();

    $values           = $theForm->getValues();
    $this->myActCmpId = Abc::$DL->companyGetCmpIdByCmpAbbr($values['cmp_abbr']);
    if ($this->myActCmpId) Http::redirect(self::getChildUrl($abc->getPagId(), $this->myActCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form for selecting the target company.
   */
  private function createCompanyForm()
  {
    $form = new CoreForm();

    // Create input control for Company abbreviation.
    $input = new TextControl('cmp_abbr');
    $input->setAttrMaxLength(C::LEN_CMP_ABBR);
    $form->addFormControl($input, 'Company', true);

    // Create "OK" submit button.
    $form->addSubmitButton(C::WRD_ID_BUTTON_OK, 'handleCompanyForm');

    return $form;
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the target company.
   */
  private function getCompany()
  {
    $form   = $this->createCompanyForm();
    $method = $form->execute();
    switch ($method)
    {
      case  'handleForm':
        $this->handleCompanyForm($form);
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

