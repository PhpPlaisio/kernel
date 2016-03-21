<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordGroupDetailsPage;
use SetBased\Abc\Core\TableColumn\DetailsIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page with an overview of all words in a word group.
 */
class WordGroupDetailsIconTableColumn extends DetailsIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target language.
   * 
   * @var int
   */
  private $myActLanId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theTargetTargetLanId The ID of the target language.
   */
  public function __construct($theTargetTargetLanId)
  {
    parent::__construct();

    $this->myActLanId = $theTargetTargetLanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($theRow)
  {
    return WordGroupDetailsPage::getUrl($theRow['wdg_id'], $this->myActLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
