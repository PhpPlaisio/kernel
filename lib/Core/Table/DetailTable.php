<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Table;

use SetBased\Abc\Core\TableAction\TableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Extents \SetBased\Html\Table\DetailTable with table actions.
 */
class DetailTable extends \SetBased\Html\Table\DetailTable
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set to false table actions of this table are not shown.
   *
   * @var array
   */
  protected $myShowTableActions = true;

  /**
   * Array with all table actions of this table.
   *
   * @var array
   */
  protected $myTablesActionGroups = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->myTablesActionGroups['default'] = [];

    // Always add an icon for exporting the data to CSV.
    // $this->addTableAction( 'default', new ExportCsvTableAction() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a table action to the list of table actions of this table.
   *
   * @param string      $theGroupName   The group to witch the table action must be added.
   * @param TableAction $theTableAction The table action.
   */
  public function addTableAction($theGroupName, $theTableAction)
  {
    $this->myTablesActionGroups[$theGroupName][] = $theTableAction;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the header for this table.
   *
   * @return string
   */
  public function getHtmlHeader()
  {
    $ret = null;

    if ($this->myShowTableActions)
    {
      $ret .= '<tr class="table_actions">';
      $ret .= '<td colspan="2">';

      $first_group = true;
      foreach ($this->myTablesActionGroups as $group)
      {
        // Add a separator between groups of table actions.
        if (!$first_group)
        {
          $ret .= '<img class="noaction" src="'.ICON_SEPARATOR.'" width="16" height="16" alt="|"/>';
        }

        // Generate HTML code for all table actions groups.
        /** @var TableAction $action */
        foreach ($group as $action)
        {
          $ret .= $action->getHtml();
        }

        if ($first_group) $first_group = false;
      }

      $ret .= '</td>';
      $ret .= '</tr>';
    }

    $ret .= parent::getHtmlHeader();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the outer HTML code of this table.
   *
   * @return string
   */
  public function getHtmlTable()
  {
    $ret = '<div class="detail_table">';
    $ret .= parent::getHtmlTable();
    $ret .= '</div>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the flag for enabling or disabling table actions. By default table actions are shown.
   *
   * @param bool $theFlag If empty table actions are not shown.
   */
  public function setShowTableActions($theFlag)
  {
    $this->myShowTableActions = ($theFlag) ? true : false;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
