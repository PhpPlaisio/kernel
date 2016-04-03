<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordTranslatePage;
use SetBased\Abc\Core\TableColumn\IconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for translating a single word.
 */
class WordTranslateIconTableColumn extends IconTableColumn
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
   * @param int $theTargetLanId The ID of the target language.
   */
  public function __construct($theTargetLanId)
  {
    parent::__construct();

    $this->myIconUrl  = ICON_SMALL_BABEL_FISH;
    $this->myAltValue = 'translate';
    $this->myActLanId = $theTargetLanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($theRow)
  {
    return WordTranslatePage::getUrl($theRow['wrd_id'], $this->myActLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
