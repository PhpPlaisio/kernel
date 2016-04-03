<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for table cells with dates.
 */
class DateTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default format of the date if the format specifier is omitted in the constructor.
   *
   * @var string
   */
  public static $ourDefaultFormat = 'd-m-Y';

  /**
   * Many (database) system use a certain value for empty dates or open end dates. Such a value must be shown as an
   * empty table cell.
   *
   * @var string
   */
  public static $ourOpenDate = '9999-12-31';

  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * The format specifier for formatting the content of this table column.
   *
   * @var string
   */
  protected $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText The header text this table column. 
   * @param string          $theFieldName  The field name of the data row used for generating this table column.
   * @param string|null     $theFormat     The format specifier for formatting the content of this table column. If null
   *                                       the default format is used.
   */
  public function __construct($theHeaderText, $theFieldName, $theFormat = null)
  {
    $this->myDataType   = 'date';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myFormat     = ($theFormat) ? $theFormat : self::$ourDefaultFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($theRow)
  {
    $value = $theRow[$this->myFieldName];

    if ($value!==false && $value!==null && $value!=='' && $theRow[$this->myFieldName]!=self::$ourOpenDate)
    {
      $date = \DateTime::createFromFormat('Y-m-d', $theRow[$this->myFieldName]);

      if ($date)
      {
        $cell = '<td class="date" data-value="';
        $cell .= $date->format('Y-m-d');
        $cell .= '">';
        $cell .= Html::txt2Html($date->format($this->myFormat));
        $cell .= '</td>';

        return $cell;
      }
      else
      {
        // The $theData[$this->myFieldName] is not a valid date.
        return '<td>'.Html::txt2Html($theRow[$this->myFieldName]).'</td>';
      }
    }
    else
    {
      // The value is an empty date.
      return '<td class="date"></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
