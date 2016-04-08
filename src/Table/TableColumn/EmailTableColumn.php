<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for table cells with email addresses.
 */
class EmailTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText The header text of this table column.
   * @param string          $theFieldName  The field name of the data rows used for generating this table column.
   */
  public function __construct($theHeaderText, $theFieldName)
  {
    $this->myDataType   = 'email';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
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
      // The value holds an email address.
      $address = Html::txt2Html($value);

      $html = '<td class="email"><a href="mailto:';
      $html .= $address;
      $html .= '">';
      $html .= $address;
      $html .= '</a></td>';

      return $html;
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
