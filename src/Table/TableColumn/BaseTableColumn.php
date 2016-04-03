<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Babel;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\OverviewTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for generating HTML code for table cells in an overview table.
 */
abstract class BaseTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The type of the data that this table column holds.
   *
   * @var string
   */
  protected $myDataType;

  /**
   * The header of this column. We distinguish 3 types:
   * <ul>
   * <li>string: the value is the header text of this table column,
   * <li>null: this table column has header without a text and with class 'empty',
   * <li>int: the value is a word ID to be resolved to a text using Babel.
   * </ul>
   *
   * Note: 14 is a word ID and '14' is a header text.
   *
   * @var string|int|null
   */
  protected $myHeaderText;

  /**
   * The sort direction of the data in this column.
   *
   * @var string
   */
  protected $mySortDirection;

  /**
   * If set the data in the table of this column is sorted or must be sorted by this column (and possible by other
   * columns) and its value determines the order in which the data of the table is sorted.
   *
   * @var int
   */
  protected $mySortOrder;

  /**
   * If set this column can be used for sorting the data in the table of this column.
   *
   * @var bool
   */
  protected $mySortable = true;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the number of columns spanned by this object.
   *
   * @return int
   */
  public function getColSpan()
  {
    return 1;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the data type of this table column.
   *
   * @return string
   */
  public function getDataType()
  {
    return $this->myDataType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for col element for this table column.
   *
   * @return string
   */
  public function getHtmlColumn()
  {
    // Add class indicating the type of data of this column.
    if ($this->myDataType)
    {
      $class = 'data-type-'.$this->myDataType;
    }
    else
    {
      $class = null;
    }

    return '<col'.Html::generateAttribute('class', $class).'/>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    if ($this->myHeaderText===null)
    {
      // If the column header is empty there is no column filter by default. This behaviour can be overridden in a
      // child class.
      return '<td></td>';
    }
    else
    {
      // The default filter is a simple text filter.
      return '<td><input type="text"/></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table header cell.
   *
   * @return string
   */
  public function getHtmlColumnHeader()
  {
    if ($this->myHeaderText===null)
    {
      $header_text = '';
      $class       = 'empty';
    }
    else
    {
      $header_text = (is_int($this->myHeaderText)) ? Babel::getWord($this->myHeaderText) : $this->myHeaderText;
      if ($this->mySortable)
      {
        // Add class indicating this column can be used for sorting.
        $class = 'sort';

        // Add class indicating the sort order of this column.
        if ($this->mySortOrder)
        {
          if ($class) $class .= ' ';

          // Add class indicating this column can be used for sorting.
          $class .= 'sort-order-';
          $class .= $this->mySortOrder;

          $class .= ($this->mySortDirection=='desc') ? ' sorted-desc' : ' sorted-asc';
        }
      }
      else
      {
        $class = null;
      }
    }

    return '<th'.Html::generateAttribute('class', $class).'>'.Html::txt2Html($header_text).'</th>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if this column has no header text.
   *
   * @return bool
   */
  public function hasEmptyHeader()
  {
    return !isset($this->myHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If this columns is sortable sets this column as not sortable (overriding the default behaviour a child class).
   * Has no effect when this column is not sortable.
   */
  public function notSortable()
  {
    $this->mySortable = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * This function is called by OverviewTable::addColumn.
   *
   * @param OverviewTable $theTable
   * @param int           $theColumnIndex
   */
  public function onAddColumn($theTable, $theColumnIndex)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the sorting order of this column.
   *
   * @param int  $theSortOrder      The sorting order.
   * @param bool $theDescendingFlag If true the data is sorted descending, otherwise ascending.
   */
  public function setSortOrder($theSortOrder, $theDescendingFlag = false)
  {
    $this->mySortDirection = ($theDescendingFlag) ? 'desc' : 'asc';
    $this->mySortOrder     = $theSortOrder;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
