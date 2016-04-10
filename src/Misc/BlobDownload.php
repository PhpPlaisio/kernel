<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Misc;

use SetBased\Affirm\Exception\FallenException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for downloading (for end user's perspective) files.
 */
class BlobDownload
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The last modification time of the file.
   *
   * @var string
   */
  protected $myDate;

  /**
   * The content disposition. One of the following possibilities:
   * <ul>
   * <li>attachment: For saving the file by the user agent.
   * <li>inline: For opening the file immediate by the user agent.
   * </ul>
   *
   * @var string
   */
  protected $myDisposition = 'inline';

  /**
   * The filename (as visible to the end user) of the file to be downloaded.
   *
   * @var string
   */
  protected $myFileName;

  /**
   * The content type of the file to be downloaded.
   *
   * @var string
   */
  protected $myMimeType;

  /**
   * If set the file is static and can be cached by the browser of the end user.
   *
   * @var bool
   */
  protected $myStatic = false;

  /**
   * The length in the bytes of the file to be downloaded.
   *
   * @var int
   */
  private $myContentLength;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the last modification date of the file.
   *
   * @param string $theDate The last modification date.
   */
  public function setDate($theDate)
  {
    $this->myDate = $theDate;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the content disposition. One of the following possibilities:
   * <ul>
   * <li>attachment: For saving the file by the user agent.
   * <li>inline: For opening the file immediate by the user agent.
   * </ul>
   * By default the content disposition is 'inline'.
   *
   * @param string $theDisposition The content disposition.
   */
  public function setDisposition($theDisposition)
  {
    $this->myDisposition = $theDisposition;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the file name as visible to the end user.
   *
   * @param string $theFileName The filename.
   */
  public function setFileName($theFileName)
  {
    $this->myFileName = $theFileName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the content type of of the file the be downloaded.
   *
   * @param string $theMimeType The content type.
   */
  public function setMimeType($theMimeType)
  {
    $this->myMimeType = $theMimeType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether the content is static or not. I.e. subsequents requests yield the same response. If the content is
   * static it can be cached by the user agent. By default he content is regarded none static.
   *
   * @param mixed $theStaticFlag If evaluates to none empty the content is static.
   */
  public function setStatic($theStaticFlag = true)
  {
    $this->myStatic = $theStaticFlag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Streams the content of a file to the user agent.
   *
   * @param string $theFileName The name of the file with the data.
   */
  public function streamFile($theFileName)
  {
    $this->setContentLength(filesize($theFileName));

    $this->headers();

    ob_clean();
    flush();
    readfile($theFileName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Streams the content of a string to the user agent.
   *
   * @param string $theString
   */
  public function streamString($theString)
  {
    $this->setContentLength(strlen($theString));

    $this->headers();

    ob_clean();
    flush();
    session_cache_limiter('');

    echo $theString;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the headers of the response to the page request.
   *
   * @throws \Exception
   */
  private function headers()
  {
    header('Content-Transfer-Encoding: binary');

    if ($this->myStatic)
    {
      header('Expires: 0');
      header('Pragma: cache');
      header('Cache-Control: public, store, cache');
    }
    else
    {
      header('Pragma: no-cache, must-revalidate, post-check=0, pre-check=0');
    }

    if ($this->myContentLength)
    {
      header('Content-Length: '.$this->myContentLength);
    }

    if ($this->myMimeType)
    {
      header('Content-Type: '.$this->myMimeType);
    }

    if ($this->myDate)
    {
      $date = date(DATE_RFC2822, strtotime($this->myDate));
      header('Last-Modified: '.$date);
    }

    switch ($this->myDisposition)
    {
      case 'inline':
        if ($this->myFileName) header("Content-Disposition: inline; filename*=UTF-8''".rawurlencode($this->myFileName));
        else                   header('Content-Disposition: inline');
        break;

      case 'attachment':
        if ($this->myFileName) header("Content-Disposition: attachment; filename*=UTF-8''".rawurlencode($this->myFileName));
        else                   header('Content-Disposition: attachment');
        break;

      default:
        throw new FallenException('disposition', $this->myDisposition);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the content length of the file.
   *
   * @param $theContentLength
   */
  private function setContentLength($theContentLength)
  {
    $this->myContentLength = $theContentLength;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
