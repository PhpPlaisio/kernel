<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc;

use SetBased\Abc\Babel\Babel;
use SetBased\Abc\BlobStore\BlobStore;
use SetBased\Abc\CanonicalHostnameResolver\CanonicalHostnameResolver;
use SetBased\Abc\DomainResolver\DomainResolver;
use SetBased\Abc\ErrorLogger\ErrorLogger;
use SetBased\Abc\Helper\WebAssets;
use SetBased\Abc\LanguageResolver\LanguageResolver;
use SetBased\Abc\Mail\MailMessage;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Obfuscator\ObfuscatorFactory;
use SetBased\Abc\RequestHandler\RequestHandler;
use SetBased\Abc\RequestLogger\RequestLogger;
use SetBased\Abc\RequestParameterResolver\RequestParameterResolver;
use SetBased\Abc\Session\Session;

/**
 * The main helper class for the ABC Framework.
 */
abstract class Abc
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The data layer generated by PhpStratum.
   *
   * @var Object
   */
  public static $DL;

  /**
   * The helper object for web assets.
   *
   * @var WebAssets
   */
  public static $assets;

  /**
   * The Babel object for retrieving linguistic entities.
   *
   * @var Babel
   */
  public static $babel;

  /**
   * The helper object for deriving the canonical hostname.
   *
   * @var CanonicalHostnameResolver
   */
  public static $canonicalHostnameResolver;

  /**
   * The helper object for deriving the domain (a.k.a. company name).
   *
   * @var DomainResolver
   */
  public static $domainResolver;

  /**
   * The helper object for resolving the code of the language in which the response must be drafted.
   *
   * @var LanguageResolver
   */
  public static $languageResolver;

  /**
   * The helper object for handling the HTTP page request.
   *
   * @var RequestHandler
   */
  public static $requestHandler;

  /**
   * The helper object for logging HTTP page requests.
   *
   * @var RequestLogger
   */
  public static $requestLogger;

  /**
   * The helper object for resolving the CGI parameters from a clean URL.
   *
   * @var RequestParameterResolver
   */
  public static $requestParameterResolver;

  /**
   * The helper object for session management.
   *
   * @var Session
   */
  public static $session;

  /**
   * The start time of serving the page request.
   *
   * @var float
   */
  public static $time0;

  /**
   * The factory for creating Obfuscators.
   *
   * @var ObfuscatorFactory
   */
  protected static $obfuscatorFactory;

  /**
   * A reference to the singleton instance of this class.
   *
   * @var Abc
   */
  private static $instance;

  /**
   * Information about the requested page.
   *
   * @var array
   */
  public $pageInfo;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  protected function __construct()
  {
    self::$instance = $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * De-obfuscates an obfuscated database ID.
   *
   * @param string $code  The obfuscated database ID.
   * @param string $alias An alias for the column holding the IDs.
   *
   * @return int
   */
  public static function deObfuscate($code, $alias)
  {
    return self::$obfuscatorFactory->decode($code, $alias);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return Abc The singleton instance.
   */
  public static function getInstance()
  {
    return self::$instance;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an Obfuscator for obfuscating and de-obfuscating database IDs.
   *
   * @param string $alias An alias for the column holding the IDs.
   *
   * @return Obfuscator
   */
  public static function getObfuscator($alias)
  {
    return self::$obfuscatorFactory->getObfuscator($alias);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Obfuscates a database ID.
   *
   * @param int    $id    The database ID.
   * @param string $alias An alias for the column holding the IDs.
   *
   * @return string
   */
  public static function obfuscate($id, $alias)
  {
    return self::$obfuscatorFactory->encode($id, $alias);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Check exist info for current page. If exist return true, otherwise false.
   *
   * {@deprecated}
   */
  public function checkPageInfo()
  {
    if (!empty($this->pageInfo)) return true;

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates an empty mail message.
   *
   * @return MailMessage
   */
  abstract public function createMailMessage();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the BLOB Store object.
   *
   * @return BlobStore
   */
  abstract public function getBlobStore();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the error logger.
   *
   * @return ErrorLogger
   */
  abstract public function getErrorLogger();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of the login page.
   *
   * @param string|null $url The requested URL. After a successful login the user agent must be redirected to this URL.
   *
   * @return string
   */
  abstract public function getLoginUrl($url);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns ID of the menu item associated with the requested page.
   *
   * {@deprecated}
   *
   * @return int
   */
  public function getMnuId()
  {
    return $this->pageInfo['mnu_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the requested page.
   *
   * {@deprecated}
   *
   * @return int
   */
  public function getPagId()
  {
    return $this->pageInfo['pag_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the "original" page.
   *
   * {@deprecated}
   *
   * @return int
   */
  public function getPagIdOrg()
  {
    return $this->pageInfo['pag_id_org'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns page group title.
   *
   * {@deprecated}
   *
   * @return string
   */
  public function getPageGroupTitle()
  {
    return $this->pageInfo['ptb_title'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the current user is authorized the request a page.
   *
   * {@deprecated}
   *
   * @param int $pagId The ID of the page.
   *
   * @return bool
   */
  public function getPathAuth($pagId)
  {
    return self::$DL->abcAuthGetPageAuth(self::$session->getCmpId(), self::$session->getProId(), $pagId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns page group title.
   *
   * {@deprecated}
   *
   * @return string
   */
  public function getPtbId()
  {
    return $this->pageInfo['ptb_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the actual page request including authorization and security checking, transaction handling,
   * request logging, and exception handling.
   */
  public function handlePageRequest()
  {
    self::$requestHandler->handleRequest();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
