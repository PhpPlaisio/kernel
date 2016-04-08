<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for deleting a word.
 */
class WordDeletePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word.
   *
   * @var int
   */
  private $myWrdId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWrdId = self::getCgiId('wrd', 'wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theWrdId The ID of the word to be deleted.
   *
   * @return string
   */
  public static function getUrl($theWrdId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_DELETE, 'pag');
    $url .= self::putCgiVar('wrd', $theWrdId, 'wrd');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $details = Abc::$DL->wordGetDetails($this->myWrdId, $this->myLanId);

    Abc::$DL->wordDeleteWord($this->myWrdId);

    Http::redirect(WordGroupDetailsPage::getUrl($details['wdg_id'], $this->myActLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
