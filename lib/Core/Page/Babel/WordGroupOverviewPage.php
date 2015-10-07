<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\OverviewTable;
use SetBased\Abc\Core\TableAction\Babel\WordGroupInsertTableAction;
use SetBased\Abc\Core\TableColumn\Babel\WordGroupDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\Babel\WordGroupUpdateIconTableColumn;
use SetBased\Html\TableColumn\NumericTableColumn;
use SetBased\Html\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page show an overview of all word groups.
 */
class WordGroupOverviewPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param bool|int $theTargetLanId
   *
   * @return string
   */
  public static function getUrl($theTargetLanId = false)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_BABEL_WORD_GROUP_OVERVIEW, 'pag');
    if ($theTargetLanId) $url .= "/?act_lan_id=$theTargetLanId";

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->myActLanId = $this->selectLanguage($this->myActLanId);

    if ($this->myActLanId) $this->showWordGroups();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the overview of all word groups.
   */
  private function showWordGroups()
  {
    $groups = Abc::$DL->wordGroupGetAll($this->myActLanId);

    $table = new OverviewTable($this->myCmpId, $this->myUsrId);

    // Table action for inserting a new word group.
    $table->addTableAction('default', new WordGroupInsertTableAction());

    // Show word group ID.
    $table->addColumn(new NumericTableColumn('ID', 'wdg_id'));

    // Show word group name.
    $col = $table->addColumn(new TextTableColumn('Word Group', 'wdg_name'));
    $col->sortOrder(1);

    // Show total words in the word group.
    $table->addColumn(new TextTableColumn('# Words', 'n1'));

    // Show total words to be translated in the word group.
    if ($this->myActLanId!=$this->myRefLanId)
    {
      $table->addColumn(new TextTableColumn('To Do', 'n2'));
    }

    // Add link to the details of the word group.
    $table->addColumn(new WordGroupDetailsIconTableColumn($this->myActLanId));

    // Add link to the modify the word group.
    $table->addColumn(new WordGroupUpdateIconTableColumn($this->myActLanId));

    echo $table->getHtmlTable($groups);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

