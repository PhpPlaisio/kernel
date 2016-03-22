<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Table;

use SetBased\Abc\Core\TableAction\RowCountTableAction;
use SetBased\Abc\Core\TableAction\TableAction;
use SetBased\Abc\Table\OverviewTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Extends \SetBased\Abc\Table\OverviewTable with table actions.
 */
class CoreOverviewTable extends OverviewTable
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set to false table actions of this table are not shown.
   *
   * @var bool
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
    // Enable filtering by default.
    $this->myFilter = true;

    $this->myTablesActionGroups['default'] = [];
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
      $colspan = $this->getNumberOfColumns();

      $ret .= '<tr class="table_actions">';
      $ret .= '<td colspan="'.$colspan.'">';

      $first_group = true;
      foreach ($this->myTablesActionGroups as $group)
      {
        // Add a separator between groups of table actions.
        if (!$first_group)
        {
          $ret .= '<img class="noaction" src="'.ICON_SEPARATOR.'" width="16" height="16" alt="|"/>';
        }

        // Generate HTML code for all table actions groups.
        /** @var $action Object */
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
   * @param array[] $theRows The data shown in the table.
   *
   * @return string
   */
  public function getHtmlTable($theRows)
  {
    // Always add row count to the default table actions.
    $this->addTableAction('default', new RowCountTableAction(count($theRows)));

    // Don't show filters if the number of rows is less or equal than 3.
    if (count($theRows)<=3) $this->myFilter = false;

    // Generate the HTML code for the table.
    $ret = '<div class="overview_table">';
    $ret .= parent::getHtmlTable($theRows);
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
