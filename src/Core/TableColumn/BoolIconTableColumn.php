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
   * @param string|int|null $theHeaderText    The header text this table column.
   * @param string          $theFieldName     The field name of the data row used for generating this table column.
   * @param bool            $theShowFalseFlag If set for false values an icon is shown, otherwise the cell is empty for
   *                                          false values.
   */
  public function __construct($theHeaderText, $theFieldName, $theShowFalseFlag = false)
  {
    $this->myDataType   = 'bool';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myShowFalse  = $theShowFalseFlag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($theRow)
  {
    $attributes = ['class' => 'bool'];

    switch (true)
    {
      case $theRow[$this->myFieldName]===1:
      case $theRow[$this->myFieldName]==='1':
        $attributes['data-value'] = 1;
        $html                     = '<img src="'.ICON_SMALL_TRUE.'" alt="1"/>';
        break;

      case $theRow[$this->myFieldName]===0:
      case $theRow[$this->myFieldName]==='0':
      case $theRow[$this->myFieldName]==='':
      case $theRow[$this->myFieldName]===null:
      case $theRow[$this->myFieldName]===false:
        $attributes['data-value'] = 0;
        $html                     = ($this->myShowFalse) ? '<img src="'.ICON_SMALL_FALSE.'" alt="0"/>' : '';
        break;

      default:
        $attributes['data-value'] = $theRow[$this->myFieldName];
        $html                     = Html::txt2Html($theRow[$this->myFieldName]);
    }

    return Html::generateElement('td', $attributes, $html, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
