<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for table cells with multiple email addresses.
 */
class MultiEmailTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The character for separating multiple email addresses in the input data.
   *
   * @var string
   */
  protected $myDataSeparator;

  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * The character for separating multiple email addresses  in the generated HTML code.
   *
   * @var string
   */
  protected $myHtmlSeparator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText    The header text for this table column.
   * @param string $theFieldName     The field name of the data rows used for generating this table column.
   * @param string $theDataSeparator The character for separating multiple email addresses in the input data.
   * @param string $theHtmlSeparator The HTML snippet for separating multiple email addresses in the generated HTML
   *                                 code.
   */
  public function __construct($theHeaderText, $theFieldName, $theDataSeparator = ',', $theHtmlSeparator = '<br/>')
  {
    $this->myDataType      = 'email';
    $this->myHeaderText    = $theHeaderText;
    $this->myFieldName     = $theFieldName;
    $this->myDataSeparator = $theDataSeparator;
    $this->myHtmlSeparator = $theHtmlSeparator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($theData)
  {
    $value = $theData[$this->myFieldName];

    if ($value!==false && $value!==null && $value!=='')
    {
      // Vales has 1 or more mail addresses.
      $addresses = explode($this->myDataSeparator, $value);

      $html = '<td>';
      foreach ($addresses as $i => $address)
      {
        if ($i) $html .= $this->myHtmlSeparator;
        $html .= '<a'.Html::generateAttribute('href', 'mailto:'.$address).'>'.Html::txt2Html($address).'</a>';
      }
      $html .= '</td>';

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
