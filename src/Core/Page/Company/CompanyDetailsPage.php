<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\TableAction\Company\CompanyUpdateTableAction;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with details of a company.
 */
class CompanyDetailsPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the company of which data is shown on this page (i.e. the target company).
   *
   * @var int
   */
  protected $myActCmpId;

  /**
   * The details of the target company.
   *
   * @var array
   */
  private $myDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myActCmpId = self::getCgiId('cmp', 'cmp');

    $this->myDetails = Abc::$DL->companyGetDetails($this->myActCmpId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($theCmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_DETAILS, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showCompanyDetails();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the details of the company.
   */
  private function showCompanyDetails()
  {
    $table = new CoreDetailTable();

    // Add table action for update the company details.
    $table->addTableAction('default', new CompanyUpdateTableAction($this->myActCmpId));

    // Show company ID.
    NumericTableRow::addRow($table, 'ID', $this->myDetails['cmp_id'], '%d');

    // Show company abbreviation.
    TextTableRow::addRow($table, 'Abbreviation', $this->myDetails['cmp_abbr']);

    // Show label.
    TextTableRow::addRow($table, 'Label', $this->myDetails['cmp_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

