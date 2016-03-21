<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Babel\WordInsertTableAction;
use SetBased\Abc\Core\TableAction\Babel\WordTranslateWordsTableAction;
use SetBased\Abc\Core\TableColumn\Babel\WordDeleteIconTableColumn;
use SetBased\Abc\Core\TableColumn\Babel\WordTranslateIconTableColumn;
use SetBased\Abc\Core\TableColumn\Babel\WordUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview of all words in a word group.
 */
class WordGroupDetailsPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word group shown on this page.
   *
   * @var array
   */
  private $myDetails;

  /**
   * The ID of the word group shown on this page.
   *
   * @var int
   */
  private $myWdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWdgId = self::getCgiId('wdg', 'wdg');

    $this->myDetails = Abc::$DL->wordGroupGetDetails($this->myWdgId);

    $this->appendPageTitle($this->myDetails['wdg_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @param int $theWdgId       The ID of the word group.
   * @param int $theTargetLanId The ID of the target language.
   *
   * @return string
   */
  public static function getUrl($theWdgId, $theTargetLanId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_GROUP_DETAILS, 'pag');
    $url .= self::putCgiVar('wdg', $theWdgId, 'wdg');
    $url .= self::putCgiVar('act_lan', $theTargetLanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoTabContent()
  {
    $this->selectLanguage();

    if ($this->myActLanId)
    {
      $this->showWordGroupInfo();

      $this->showWords();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the word group.
   */
  private function showWordGroupInfo()
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the word group.
    NumericTableRow::addRow($table, 'ID', $this->myDetails['wdg_id'], '%d');

    // Add row for the name of the word group.
    TextTableRow::addRow($table, 'Word Group', $this->myDetails['wdg_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview of all words in a word group.
   */
  private function showWords()
  {
    // Determine whether the user is a translator.
    $is_translator = ($this->myActLanId!=$this->myRefLanId &&
      Abc::$DL->authGetPageAuth($this->myCmpId, $this->myProId, C::PAG_ID_BABEL_WORD_TRANSLATE));

    $words = Abc::$DL->wordGroupGetAllWordsTranslator($this->myWdgId, $this->myActLanId);

    $ref_language = Abc::$DL->languageGetName($this->myRefLanId, $this->myRefLanId);
    $act_language = Abc::$DL->LanguageGetName($this->myActLanId, $this->myRefLanId);

    $table = new CoreOverviewTable();

    // Add action for inserting a new word to the word group.
    $table->addTableAction('default', new WordInsertTableAction($this->myWdgId));

    // Add action for translation all words in the word group.
    if ($is_translator)
    {
      $table->addTableAction('default', new WordTranslateWordsTableAction($this->myWdgId, $this->myActLanId));
    }

    // Show word ID.
    $table->addColumn(new NumericTableColumn('ID', 'wrd_id'));

    // Show label of word.
    // Show target text.
    if ($this->myActLanId==$this->myRefLanId)
    {
      $table->addColumn(new TextTableColumn('Label', 'wrd_label'));
    }

    // Show reference text.
    $col = $table->addColumn(new TextTableColumn($ref_language, 'ref_wdt_text'));
    $col->sortOrder(1);

    // Show target text.
    if ($this->myActLanId!=$this->myRefLanId)
    {
      $table->addColumn(new TextTableColumn($act_language, 'act_wdt_text'));
    }

    // Show remark.
    $table->addColumn(new TextTableColumn('Comment', 'wrd_comment'));

    // Show link to translate the word.
    if ($is_translator)
    {
      $table->addColumn(new WordTranslateIconTableColumn($this->myActLanId));
    }

    // Show link to modify the word.
    $table->addColumn(new WordUpdateIconTableColumn());

    // Show link to delete the word.
    if (Abc::$DL->authGetPageAuth($this->myCmpId, $this->myProId, C::PAG_ID_BABEL_WORD_DELETE))
    {
      $table->addColumn(new WordDeleteIconTableColumn());
    }

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($words);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
