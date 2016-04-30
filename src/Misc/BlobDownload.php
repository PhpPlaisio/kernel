<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Misc;

use SetBased\Exception\FallenException;

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
  protected $date;

  /**
   * The content disposition. One of the following possibilities:
   * <ul>
   * <li>attachment: For saving the file by the user agent.
   * <li>inline: For opening the file immediate by the user agent.
   * </ul>
   *
   * @var string
   */
  protected $disposition = 'inline';

  /**
   * The filename (as visible to the end user) of the file to be downloaded.
   *
   * @var string
   */
  protected $fileName;

  /**
   * The content type of the file to be downloaded.
   *
   * @var string
   */
  protected $mimeType;

  /**
   * If set the file is static and can be cached by the browser of the end user.
   *
   * @var bool
   */
  protected $static = false;

  /**
   * The length in the bytes of the file to be downloaded.
   *
   * @var int
   */
  private $contentLength;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the last modification date of the file.
   *
   * @param string $date The last modification date.
   */
  public function setDate($date)
  {
    $this->date = $date;
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
   * @param string $disposition The content disposition.
   */
  public function setDisposition($disposition)
  {
    $this->disposition = $disposition;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the file name as visible to the end user.
   *
   * @param string $filename The filename.
   */
  public function setFileName($filename)
  {
    $this->fileName = $filename;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the content type of of the file the be downloaded.
   *
   * @param string $mimeType The content type.
   */
  public function setMimeType($mimeType)
  {
    $this->mimeType = $mimeType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether the content is static or not. I.e. subsequents requests yield the same response. If the content is
   * static it can be cached by the user agent. By default he content is regarded none static.
   *
   * @param mixed $staticFlag If evaluates to none empty the content is static.
   */
  public function setStatic($staticFlag = true)
  {
    $this->static = $staticFlag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Streams the content of a file to the user agent.
   *
   * @param string $filename The name of the file with the data.
   */
  public function streamFile($filename)
  {
    $this->setContentLength(filesize($filename));

    $this->headers();

    ob_clean();
    flush();
    readfile($filename);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Streams the content of a string to the user agent.
   *
   * @param string $string
   */
  public function streamString($string)
  {
    $this->setContentLength(strlen($string));

    $this->headers();

    ob_clean();
    flush();
    session_cache_limiter('');

    echo $string;
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

    if ($this->static)
    {
      header('Expires: 0');
      header('Pragma: cache');
      header('Cache-Control: public, store, cache');
    }
    else
    {
      header('Pragma: no-cache, must-revalidate, post-check=0, pre-check=0');
    }

    if ($this->contentLength)
    {
      header('Content-Length: '.$this->contentLength);
    }

    if ($this->mimeType)
    {
      header('Content-Type: '.$this->mimeType);
    }

    if ($this->date)
    {
      $date = date(DATE_RFC2822, strtotime($this->date));
      header('Last-Modified: '.$date);
    }

    switch ($this->disposition)
    {
      case 'inline':
        if ($this->fileName) header("Content-Disposition: inline; filename*=UTF-8''".rawurlencode($this->fileName));
        else                   header('Content-Disposition: inline');
        break;

      case 'attachment':
        if ($this->fileName) header("Content-Disposition: attachment; filename*=UTF-8''".rawurlencode($this->fileName));
        else                   header('Content-Disposition: attachment');
        break;

      default:
        throw new FallenException('disposition', $this->disposition);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the content length of the file.
   *
   * @param $contentLength
   */
  private function setContentLength($contentLength)
  {
    $this->contentLength = $contentLength;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
