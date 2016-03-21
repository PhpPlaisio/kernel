<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\SpecificPageDeletePage;
use SetBased\Abc\Core\TableColumn\DeleteIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table with column for deleting a company specific page that overrides a standard page.
 */
class SpecificPageDeleteIconTableColumn extends DeleteIconTableColumn
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
    $this->myConfirmMessage = 'Remove page "'.$theRow['pag_class_child'].'?'; // xxxbbl

    return SpecificPageDeletePage::getUrl($this->myTargetCmpId, $theRow['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
