<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract table column for columns with icons.
 */
abstract class IconTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of the alt attribute of the icon.
   *
   * @var
   */
  protected $myAltValue;

  /**
   * If set the will be prompted with an confirm message before the link is followed.
   *
   * @var string
   */
  protected $myConfirmMessage;

  /**
   * The URL of the icon.
   *
   * @var string
   */
  protected $myIconUrl;

  /**
   * If set to true the icon is a download link (e.g. a PDF file).
   *
   * @var bool
   */
  protected $myIsDownloadLink = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->sortable = false;
    $this->dataType = 'none';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $row
   *
   * @return string
   */
  public function getHtmlCell($row)
  {
    $url = $this->getUrl($row);

    $ret = '<td>';
    if ($url)
    {
      $ret .= '<a';
      $ret .= Html::generateAttribute('href', $url);
      $ret .= ' class="icon_action"';
      if ($this->myIsDownloadLink) $ret .= ' target="_blank"';
      $ret .= '>';
    }

    $ret .= '<img';
    $ret .= Html::generateAttribute('src', $this->myIconUrl);
    $ret .= ' width="12"';
    $ret .= ' height="12"';
    $ret .= ' class="icon"';

    if ($this->myConfirmMessage) $ret .= Html::generateAttribute('data-confirm-message', $this->myConfirmMessage);

    $ret .= Html::generateAttribute('alt', $this->myAltValue);
    $ret .= '/>';

    if ($url) $ret .= '</a>';

    $ret .= '</td>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of the link of the icon for the row.
   *
   * @param array $theRow The data row.
   *
   * @return string
   */
  abstract public function getUrl($theRow);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
