<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableRow;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table row in a detail table with a date.
 */
class DateTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default format of the date if the format specifier is omitted in the constructor.
   *
   * @var string
   */
  public static $ourDefaultFormat = 'd-m-Y';

  /**
   * Many (database) systems use a certain value for empty dates or open end dates. Such a value must be shown as an
   * empty table cell.
   *
   * @var string
   */
  public static $ourOpenDate = '9999-12-31';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a date value to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param string      $theValue  The date in YYYY-MM-DD format.
   * @param string|null $theFormat The format specifier for formatting the content of this table column. If null
   *                               the default format is used.
   */
  public static function addRow($theTable, $theHeader, $theValue, $theFormat = null)
  {
    $row = '<tr>';

    $row .= '<th>';
    $row .= Html::txt2Html($theHeader);
    $row .= '</th>';

    if ($theValue && $theValue!=self::$ourOpenDate)
    {
      $date = \DateTime::createFromFormat('Y-m-d', $theValue);

      if ($date)
      {
        // The $theValue is a valid date.
        $format = ($theFormat) ? $theFormat : self::$ourDefaultFormat;
        $row .= '<td class="date" data-value="';
        $row .= $date->format('Y-m-d');
        $row .= '">';
        $row .= Html::txt2Html($date->format($format));
        $row .= '</td>';
      }
      else
      {
        // The $theValue is not a valid date.
        $row .= '<td>';
        $row .= Html::txt2Html($theValue);
        $row .= '</td>';
      }
    }
    else
    {
      // The $theValue is empty.
      $row .= '<td class="date"></td>';
    }

    $row .= '</tr>';

    $theTable->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
