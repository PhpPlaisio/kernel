<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract page for viewing or downloading a BLOB.
 */
abstract class BlobDownloadPage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The BLOB data, i.e. a row from ABC_BLOB and ABC_BLOB_DATA.
   *
   * @var array
   */
  protected $myBlob;

  /**
   * The disposition type (inline or attachment)
   *
   * @var string
   */
  protected $myDispositionType = 'inline';

  //--------------------------------------------------------------------------------------------------------------------
  public function echoPage()
  {
    $date    = date(DATE_RFC2822, strtotime($this->myBlob['blb_date']));
    $expires = date(DATE_RFC2822, time() + 365 * 24 * 60 * 60);

    ob_clean();
    flush();
    session_cache_limiter('');

    switch ($this->myDispositionType)
    {
      case 'attachment' :
        header('Pragma: cache');
        header('Cache-Control: public, store, cache');
        header('Expires: '.$expires);
        header('Last-Modified: '.$date);
        header('Content-Disposition: attachment; filename*=UTF-8\'\''.rawurlencode($this->myBlob['blb_file_name']));
        header('Content-Type: application/force-download');
        header('Content-Length: '.$this->myBlob['blb_size']);
        break;

      case 'inline' :
        header('Pragma: cache');
        header('Cache-Control: public, store, cache');
        header('Expires: '.$expires);
        header('Last-Modified: '.$date);
        header('Content-Length: '.$this->myBlob['blb_size']);
        header('Content-Type: '.$this->myBlob['blb_mime_type']);
        // @todo use encoding as in HTML or DataLayer.
        header('Content-Disposition: inline; filename*=UTF-8\'\''.rawurlencode($this->myBlob['blb_file_name']));
        break;
    }

    echo $this->myBlob['bdt_data'];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
