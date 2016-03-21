<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\SpecificPageUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for updating the details of a company specific page that overrides a standard
 * page.
 */
class SpecificPageUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target company.
   * 
   * @var int
   */
  private $myTargetCmpId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theTargetCmpId The ID of the target company.
   */
  public function __construct($theTargetCmpId)
  {
    parent::__construct();

    $this->myTargetCmpId = $theTargetCmpId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($theRow)
  {
    return SpecificPageUpdatePage::getUrl($this->myTargetCmpId, $theRow['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
