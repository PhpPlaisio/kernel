<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for table cells with numbers.
 */
class NumericTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
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
   * @param string          $theFormat     The format specifier for formatting the content of this table column. See
   *                                       sprintf.
   */
  public function __construct($theHeaderText, $theFieldName, $theFormat = '%d')
  {
    $this->myDataType   = 'numeric';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myFormat     = $theFormat;
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
      return '<td class="number">'.Html::txt2Html(sprintf($this->myFormat, $value)).'</td>';
    }
    else
    {
      // The value is empty.
      return '<td></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
