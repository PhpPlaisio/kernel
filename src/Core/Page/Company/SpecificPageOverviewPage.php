<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\SpecificPageInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\SpecificPageDeleteIconTableColumn;
use SetBased\Abc\Core\TableColumn\Company\SpecificPageUpdateIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageDetailsIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview of all company specific pages for the target company.
 */
class SpecificPageOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the company specific pages.
   * 
   * @var array[]
   */
  private $myPages;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myPages = Abc::$DL->companySpecificPageGetAll($this->myActCmpId, $this->myLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $theCmpId The ID of the target company.
   *
   * @return string The URL of this page.
   */
  public static function getUrl($theCmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_OVERVIEW, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $table = new CoreOverviewTable($this->myCmpId, $this->myUsrId);

    $table->addTableAction('default', new SpecificPageInsertTableAction($this->myActCmpId));

    // Add column with page ID.
    $table->addColumn(new NumericTableColumn('ID', 'pag_id'));

    // Add column with page title.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Add column with name of parent class.
    $column = $table->addColumn(new TextTableColumn('Parent Class', 'pag_class_parent'));
    $column->sortOrder(1);

    // Add column with name of child class.
    $table->addColumn(new TextTableColumn('Child Class', 'pag_class_child'));

    // Add column with link to the details of the page.
    $table->addColumn(new PageDetailsIconTableColumn());

    // Add column with link to modify Company specific page.
    $table->addColumn(new SpecificPageUpdateIconTableColumn($this->myActCmpId));

    // Add column with link to delete Company specific page.
    $table->addColumn(new SpecificPageDeleteIconTableColumn($this->myActCmpId));

    echo $table->getHtmlTable($this->myPages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
