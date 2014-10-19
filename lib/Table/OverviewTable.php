<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Table;

use SetBased\Html\Html;
use SetBased\Html\TableColumn\DualTableColumn;
use SetBased\Html\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
class OverviewTable
{

  /**
   * The attributes of the table tag of this table.
   *
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * The objects for generating the columns of this table.
   *
   * @var TableColumn[]
   */
  protected $myColumns = array();

  /**
   * If set to true the header will contain a row for filtering.
   *
   * @var bool
   */
  protected $myFilter = false;

  /**
   * The title of this table.
   *
   * @var string
   */
  protected $myTitle;

  /**
   * The index in $myColumns of the next column added to this table.
   *
   * @var int
   */
  private $myColIndex = 1;

  /**
   * If set to true the table is sortable.
   *
   * @var bool
   */
  private $mySortable = true;


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds @a $theColumn to this table and returns the column.
   *
   * @param TableColumn $theColumn
   *
   * @return TableColumn|DualTableColumn
   */
  public function addColumn( $theColumn )
  {
    // Add the column to our array of columns.
    $this->myColumns[$this->myColIndex] = $theColumn;

    $theColumn->onAddColumn( $this, $this->myColIndex );

    // Increase the index for the next added column.
    $this->myColIndex += $theColumn->getColSpan();

    return $theColumn;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disables table filtering.
   */
  public function disableFilter()
  {
    $this->myFilter = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disables sorting for all columns in table.
   */
  public function disableSorting()
  {
    $this->mySortable = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables table filtering.
   */
  public function enableFilter()
  {
    $this->myFilter = true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of this table displaying @a $theRows as data.
   *
   * @param array $theRows
   *
   * @return string
   */
  public function getHtmlTable( &$theRows )
  {
    $ret = $this->getHtmlPrefix();

    $ret .= '<table';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';


    // Generate HTML code for the column classes.
    $ret .= '<colgroup>';
    foreach ($this->myColumns as $column)
    {
      // If required disable sorting of this column.
      if (!$this->mySortable) $column->notSortable();

      // Generate column element.
      $ret .= $column->getHtmlColumn();
    }
    $ret .= '</colgroup>';


    // Generate HTML code for the table header.
    $ret .= '<thead>';
    $ret .= $this->getHtmlHeader();
    $ret .= '</thead>';


    // Generate HTML code for the table body.
    $ret .= '<tbody>';
    $ret .= $this->getHtmlBody( $theRows );
    $ret .= '</tbody>';


    $ret .= '</table>';

    $ret .= $this->getHtmlPostfix();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the total number of columns of this table.
   *
   * @return int
   */
  public function getNumberOfColumns()
  {
    return $this->myColIndex - 1;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the title of this table.
   */
  public function getTitle()
  {
    return $this->myTitle;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute @a $theName of this table to @a $theValue.
   * * If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
   * * If @a $theName is 'class' the @a $theValue is appended to space separated list of classes (unless the above rule
   *   applies.)
   *
   * @param $theName  string      The name of the attribute.
   * @param $theValue string|null The value for the attribute.
   */
  public function setAttribute( $theName, $theValue )
  {
    if ($theValue===null || $theValue===false || $theValue==='')
    {
      unset($this->myAttributes[$theName]);
    }
    else
    {
      if ($theName==='class' && isset($this->myAttributes[$theName]))
      {
        $this->myAttributes[$theName] .= ' ';
        $this->myAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this table.
   *
   * @param string $theTitle The title.
   */
  public function setTitle( $theTitle )
  {
    $this->myTitle = $theTitle;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the body for this table holding @a $theRows as data.
   *
   * @param array $theRows
   *
   * @return string
   */
  protected function getHtmlBody( &$theRows )
  {
    $ret = '';
    $i   = 0;
    foreach ($theRows as $row)
    {
      if ($i % 2==0) $ret .= '<tr class="even">';
      else           $ret .= '<tr class="odd">';
      foreach ($this->myColumns as $column)
      {
        $ret .= $column->getHtmlCell( $row );
      }
      $ret .= '</tr>';
      $i++;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the header for this table.
   *
   * @return string
   */
  protected function getHtmlHeader()
  {
    $ret = '';

    if ($this->myTitle)
    {
      $mode    = 1;
      $colspan = 0;

      $ret .= '<tr class="title">';
      foreach ($this->myColumns as $column)
      {
        $empty = $column->hasEmptyHeader();

        if ($mode==1)
        {
          if ($empty)
          {
            $ret .= '<th class="empty"></th>';
          }
          else
          {
            $mode = 2;
          }
        }

        if ($mode==2)
        {
          if ($empty)
          {
            $mode = 3;
          }
          else
          {
            $colspan++;
          }
        }

        if ($mode==3)
        {
          if ($colspan==1) $colspan = null;
          $ret .= '<th'.Html::generateAttribute( 'colspan', $colspan ).'>'.Html::txt2Html( $this->myTitle ).'</th>';
          $mode = 4;
        }

        if ($mode==4)
        {
          $ret .= '<th class="empty"></th>';
        }
      }

      if ($mode==2)
      {
        if ($colspan==1) $colspan = null;
        $ret .= '<th'.Html::generateAttribute( 'colspan', $colspan ).'>'.Html::txt2Html( $this->myTitle ).'</th>';
      }

      $ret .= '</tr>';
    }

    $ret .= '<tr class="header">';
    foreach ($this->myColumns as $column)
    {
      $ret .= $column->getHtmlColumnHeader();
    }
    $ret .= '</tr>';

    if ($this->myFilter)
    {
      $ret .= '<tr class="filter">';
      foreach ($this->myColumns as $column)
      {
        $ret .= $column->getHtmlColumnFilter();
      }
      $ret .= '</tr>';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code inserted before the HTML code of the table.
   *
   * @return string
   */
  protected function getHtmlPostfix()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code appended after the HTML code of the table.
   *
   * @return string
   */
  protected function getHtmlPrefix()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------