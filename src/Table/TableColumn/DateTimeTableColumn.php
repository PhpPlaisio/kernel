<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for table cells with dates and times.
 */
class DateTimeTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default format of the date-time if the format specifier is omitted in the constructor.
   *
   * @var string
   */
  public static $ourDefaultFormat = 'd-m-Y H:i:s';

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
   * @param string|int|null $theHeaderText The header text of this table column.
   * @param string          $theFieldName  The field name of the data row used for generating this table column.
   * @param string|null     $theFormat     The format specifier for formatting the content of this table column. If null
   *                                       the default format is used.
   */
  public function __construct($theHeaderText, $theFieldName, $theFormat = null)
  {
    $this->myDataType   = 'datetime';
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

    if ($value!==false && $value!==null && $value!=='')
    {
      $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $theRow[$this->myFieldName]);

      if ($datetime)
      {
        $cell = '<td class="datetime" data-value="';
        $cell .= $datetime->format('Y-m-d H:i:s');
        $cell .= '">';
        $cell .= Html::txt2Html($datetime->format($this->myFormat));
        $cell .= '</td>';

        return $cell;
      }
      else
      {
        // The value is not a valid datetime.
        return '<td>'.Html::txt2Html($theRow[$this->myFieldName]).'</td>';
      }
    }
    else
    {
      // The value is an empty datetime.
      return '<td class="datetime"></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
