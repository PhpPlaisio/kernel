<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for cells with an icon for boolean values.
 */
class BoolIconTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * If set false values are shown explicitly. Otherwise when the value evaluates to false an empty cell is shown.
   *
   * @var bool
   */
  private $myShowFalse;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $theHeaderText    The header text of this table column.
   * @param string          $theFieldName     The field name of the data row used for generating this table column.
   * @param bool            $theShowFalseFlag If set for false values an icon is shown, otherwise the cell is empty for
   *                                          false values.
   */
  public function __construct($theHeaderText, $theFieldName, $theShowFalseFlag = false)
  {
    $this->dataType    = 'bool';
    $this->headerText  = $theHeaderText;
    $this->myFieldName = $theFieldName;
    $this->myShowFalse = $theShowFalseFlag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($row)
  {
    $attributes = ['class' => 'bool'];

    switch (true)
    {
      case $row[$this->myFieldName]===1:
      case $row[$this->myFieldName]==='1':
        $attributes['data-value'] = 1;
        $html                     = '<img src="'.ICON_SMALL_TRUE.'" alt="1"/>';
        break;

      case $row[$this->myFieldName]===0:
      case $row[$this->myFieldName]==='0':
      case $row[$this->myFieldName]==='':
      case $row[$this->myFieldName]===null:
      case $row[$this->myFieldName]===false:
        $attributes['data-value'] = 0;
        $html                     = ($this->myShowFalse) ? '<img src="'.ICON_SMALL_FALSE.'" alt="0"/>' : '';
        break;

      default:
        $attributes['data-value'] = $row[$this->myFieldName];
        $html                     = Html::txt2Html($row[$this->myFieldName]);
    }

    return Html::generateElement('td', $attributes, $html, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
