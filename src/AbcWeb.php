<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc;

use SetBased\Abc\Error\InvalidUrlException;
use SetBased\Abc\Error\NotAuthorizedException;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Page\Page;
use SetBased\Stratum\Exception\ResultException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * The main helper class for the ABC Abc.
 */
abstract class AbcWeb extends Abc
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The start time of serving the page request.
   *
   * @var float
   */
  public static $time0;

  /**
   * If set details must be logged together with the request log.
   *
   * @var true|null
   */
  protected $logRequestDetails;

  /**
   * Information about the session.
   *
   * @var array
   */
  protected $sessionInfo;

  /**
   * The page for handling the HTTP request.
   *
   * @var Page
   */
  private $page;

  /**
   * Information about the requested page.
   *
   * @var array
   */
  private $pageInfo;

  /**
   * The size of the generated page.
   *
   * @var int
   */
  private $pageSize;

  /**
   * The request log ID (rql_id).
   *
   * @var int
   */
  private $rqlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Appends with a separator a string to the page title
   *
   * @param string $pageTitleAddendum The string to eb append to the page title.
   */
  public function appendPageTitle($pageTitleAddendum)
  {
    $this->pageInfo['pag_title'] .= ' - ';
    $this->pageInfo['pag_title'] .= $pageTitleAddendum;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Check exist info for current page. If exist return true, otherwise false.
   */
  public function checkPageInfo()
  {
    if (!empty($this->pageInfo)) return true;

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns stateful double submit token to prevent CSRF attacks.
   *
   * @return string
   */
  public function getCsrfToken()
  {
    return $this->sessionInfo['ses_csrf_token'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the code of the preferred language of the requestor.
   *
   * @return string
   */
  public function getLanCode()
  {
    return $this->sessionInfo['lan_code'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the preferred language of the requestor.
   *
   * @return int
   */
  public function getLanId()
  {
    return $this->sessionInfo['lan_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns ID of the menu item associated with the requested page.
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
   * @return int
   */
  public function getPagIdOrg()
  {
    return $this->pageInfo['pag_id_org'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the class for handling the page request.
   *
   * @return string
   */
  public function getPageClass()
  {
    return $this->pageInfo['pag_class'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns page group title.
   *
   * @return string
   */
  public function getPageGroupTitle()
  {
    return $this->pageInfo['ptb_title'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns info of the requested page.
   *
   * @return array
   */
  public function getPageInfo()
  {
    return $this->pageInfo;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the page title.
   *
   * @return string
   */
  public function getPageTitle()
  {
    return $this->pageInfo['pag_title'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the current user is authorized the request a page.
   *
   * @param int $pagId The ID of the page.
   *
   * @return bool
   */
  public function getPathAuth($pagId)
  {
    return self::$DL->authGetPageAuth($this->sessionInfo['cmp_id'], $this->sessionInfo['pro_id'], $pagId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the profile ID of the requestor.
   *
   * @return int
   */
  public function getProId()
  {
    return $this->sessionInfo['pro_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns page group title.
   *
   * @return string
   */
  public function getPtbId()
  {
    return $this->pageInfo['ptb_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the session.
   *
   * @return int
   */
  public function getSesId()
  {
    return $this->sessionInfo['ses_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the session info.
   *
   * @return array
   */
  public function getSessionInfo()
  {
    return $this->sessionInfo;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the user ID of the requestor.
   *
   * @return int
   */
  public function getUsrId()
  {
    return $this->sessionInfo['usr_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the actual page request including authorization and security checking, transaction handling,
   * request logging, and exception handling.
   */
  public function handlePageRequest()
  {
    // Start output buffering.
    ob_start();

    try
    {
      // Derive the canonical server name aka fully qualified server name.
      $this->setCanonicalServerName();

      // Derive the domain (a.k.a. company abbreviation).
      $this->setDomain();

      // Get the CGI variables from a clean URL.
      $this->uncleanUrl();

      // Retrieve the session or create an new session.
      $this->getSession();

      // Test the user is authorized for the requested page.
      $this->checkAuthorization();

      $page_class = $this->pageInfo['pag_class'];
      try
      {
        $this->page = new $page_class();
      }
      catch (ResultException $e)
      {
        // A ResultException during the construction of a page object is (almost) always caused by an invalid URL.
        throw new InvalidUrlException('No data found', $e);
      }

      // Perform addition authorization and security checks.
      $this->page->checkAuthorization();

      $uri = $this->page->getPreferredUri();
      if (isset($uri) && $uri!=$_SERVER['REQUEST_URI'])
      {
        // The preferred URI differs from the requested URI. Redirect the user agent to the preferred URL.
        self::$DL->rollback();
        HttpHeader::redirectMovedPermanently($uri);
      }
      else
      {
        // Echo the page content.
        $this->page->echoPage();

        // Flush the page content.
        if (ob_get_level()) ob_flush();

        $this->pageSize = $this->page->getPageSize();
      }
    }
    catch (NotAuthorizedException $e)
    {
      // The user has no authorization for the requested URL.
      $this->handleNotAuthorizedException($e);
    }
    catch (InvalidUrlException $e)
    {
      // The URL is invalid.
      $this->handleInvalidUrlException($e);
    }
    catch (\Exception $e)
    {
      // Some other exception has occurred.
      $this->handleException($e);
    }

    $this->updateSession();
    $this->requestLog();

    self::$DL->commit();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the requestor is anonymous. Returns false otherwise.
   *
   * @return bool
   */
  public function isAnonymous()
  {
    return (!empty($this->sessionInfo['usr_anonymous']));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets title for current page.
   *
   * @param string $pageTitle The new title of the page.
   */
  public function setPageTitle($pageTitle)
  {
    $this->pageInfo['pag_title'] = $pageTitle;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of the login page.
   *
   * @param string|null $url The requested URL. After a successful login the user agent must be redirected to this URL.
   *
   * @return string
   */
  abstract protected function getLoginUrl($url);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles any other caught exception.
   *
   * @param \Exception $exception The caught exception.
   */
  protected function handleException($exception)
  {
    $this->logException($exception);
    self::$DL->rollback();

    // Set the HTTP status to 500 (Internal Server Error).
    HttpHeader::serverErrorInternalServerError();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles a caught InvalidUrlException.
   *
   * @param InvalidUrlException $exception The caught exception.
   */
  protected function handleInvalidUrlException($exception)
  {
    $this->logException($exception);
    self::$DL->rollback();

    // Set the HTTP status to 404 (Not Found).
    HttpHeader::clientErrorNotFound();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles a caught NotAuthorizedException.
   *
   * @param NotAuthorizedException $exception The caught exception.
   */
  protected function handleNotAuthorizedException($exception)
  {
    if ($this->isAnonymous())
    {
      // The user is not logged on and most likely the user has requested a page for which the user must be logged on.
      self::$DL->rollback();
      // Redirect the user agent to the login page. After the user has successfully logged on the user agent will be
      // redirected to currently requested URL.

      HttpHeader::redirectSeeOther($this->getLoginUrl($_SERVER['REQUEST_URI']));
    }
    else
    {
      // The user is logged on and the user has requested an URL for which the user has no authorization.
      $this->logException($exception);
      self::$DL->rollback();

      // Set the HTTP status to 404 (Not Found).
      HttpHeader::clientErrorNotFound();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Gets the CGI variables from the clean URL and enhances $_GET.
   */
  protected function uncleanUrl()
  {
    $uri = (isset($_SERVER['REQUEST_URI'])) ? substr($_SERVER['REQUEST_URI'], 1) : '';
    $i   = strpos($uri, '?');
    if ($i!==false) $uri = substr($uri, 0, $i);

    $urlParts = explode('/', $uri);

    $urlPartsCount = count($urlParts);
    if ($urlPartsCount % 2!=0) $urlPartsCount++;
    for ($i = 0; $i<$urlPartsCount; $i += 2)
    {
      $key        = $urlParts[$i];
      $value      = (isset($urlParts[$i + 1])) ? $urlParts[$i + 1] : null;
      $_GET[$key] = urldecode($value);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Retrieves information about the requested page and checks if the user has the correct authorization for the
   * requested page.
   */
  private function checkAuthorization()
  {
    if (isset($_GET['pag']))
    {
      $pag_id    = self::deObfuscate($_GET['pag'], 'pag');
      $pag_alias = null;
    }
    else if (isset($_GET['page']))
    {
      $pag_id    = null;
      $pag_alias = $_GET['page'];
    }
    else
    {
      $pag_id    = C::PAG_ID_MISC_INDEX;
      $pag_alias = null;
    }

    $this->pageInfo = self::$DL->authGetPageInfo($this->sessionInfo['cmp_id'],
                                                 $pag_id,
                                                 $this->sessionInfo['pro_id'],
                                                 $this->sessionInfo['lan_id'],
                                                 $pag_alias);
    if (!$this->pageInfo)
    {
      if (isset($pag_id))
      {
        throw new NotAuthorizedException('User %d is not authorized for page ID=%d.',
                                         $this->sessionInfo['usr_id'],
                                         $pag_id);
      }
      else
      {
        throw new NotAuthorizedException("User %d is not authorized for page alias='%s'.",
                                         $this->sessionInfo['usr_id'],
                                         $pag_alias);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Retrieves the session from the database based on the session cookie (ses_session_token) and sets the cookies
   * ses_session_token and ses_csrf_token.
   */
  private function getSession()
  {
    $cookie            = isset($_COOKIE['ses_session_token']) ? $_COOKIE['ses_session_token'] : null;
    $this->sessionInfo = self::$DL->sessionGetSession($this->domain, $cookie);

    if (isset($_SERVER['HTTPS']))
    {
      // Set session and CSRF cookies.
      setcookie('ses_session_token',
                $this->sessionInfo['ses_session_token'],
                false,
                '/',
                $_SERVER['HTTP_HOST'],
                true,
                true);
      setcookie('ses_csrf_token',
                $this->sessionInfo['ses_csrf_token'],
                false,
                '/',
                $_SERVER['HTTP_HOST'],
                true,
                false);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Writes a exception message to a log file.
   *
   * To the log file are written:
   * * The exception message.
   * * The stack trace.
   * * The server variables $_SERVER.
   * * The post variables $_POST.
   * * The cgi parameters $_GET.
   * * The environment variables $_ENV.
   * * The file variables $_FILE.
   * * The session info $sssionInfo.
   * * The page info $pageInfo.
   *
   * @param \Exception $exception
   */
  private function logException($exception)
  {
    list($usec, $sec) = explode(' ', microtime());
    $file_name = DIR_ERROR.'/error-'.($sec + $usec).'.log';
    $fp        = fopen($file_name, 'a');

    $message = '';
    $e       = $exception;
    while ($e)
    {
      $message .= $e->getMessage();
      $message .= "\n\n";

      $message .= $e->getTraceAsString();
      $message .= "\n\n";

      $e = $e->getPrevious();
      if ($e)
      {
        $message .= 'This exception has been caused by the following exception:';
        $message .= "\n";
      }
    }

    $message .= "Server variables\n";
    $message .= print_r($_SERVER, true);
    $message .= "\n\n";

    $message .= "Post variables\n";
    $message .= print_r($_POST, true);
    $message .= "\n\n";

    $message .= "Get variables\n";
    $message .= print_r($_GET, true);
    $message .= "\n\n";

    $message .= "Cookie variables\n";
    $message .= print_r($_COOKIE, true);
    $message .= "\n\n";

    $message .= "Environment variables\n";
    $message .= print_r($_ENV, true);
    $message .= "\n\n";

    $message .= "File variables\n";
    $message .= print_r($_FILES, true);
    $message .= "\n\n";

    $message .= "Session info\n";
    $message .= print_r($this->sessionInfo, true);
    $message .= "\n\n";

    $message .= "System info\n";
    $message .= print_r($this->pageInfo, true);
    $message .= "\n\n";

    fwrite($fp, $message);
    fclose($fp);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the page request in to the DB.
   */
  private function requestLog()
  {
    $this->rqlId = self::$DL->requestLogInsertRequest(
      $this->sessionInfo['ses_id'],
      $this->sessionInfo['cmp_id'],
      $this->sessionInfo['usr_id'],
      $this->pageInfo['pag_id'],
      mb_substr($_SERVER['REQUEST_URI'], 0, C::LEN_RQL_REQUEST),
      mb_substr($_SERVER['REQUEST_METHOD'], 0, C::LEN_RQL_METHOD),
      (isset($_SERVER['HTTP_REFERER'])) ? mb_substr($_SERVER['HTTP_REFERER'], 0, C::LEN_RQL_REFERER) : null,
      $_SERVER['REMOTE_ADDR'],
      (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, C::LEN_RQL_LANGUAGE) : null,
      (isset($_SERVER['HTTP_USER_AGENT'])) ? mb_substr($_SERVER['HTTP_USER_AGENT'], 0, C::LEN_RQL_USER_AGENT) : null,
      0, // XXX query count
      microtime(true) - self::$time0,
      $this->pageSize);

    if ($this->logRequestDetails==true)
    {
      $this->requestLogQuery();
      $this->requestLogPost($_POST);
      $this->requestLogCookie($_COOKIE);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the (by the client) sent cookies in to the database.
   *
   * Usage on this method on production environments is disguised.
   *
   * @param array       $cookies  must be $_COOKIES
   * @param string|null $variable must not be used, intended for use by recursive calls only.
   */
  private function requestLogCookie($cookies, $variable = null)
  {
    if (is_array($cookies))
    {
      foreach ($cookies as $index => $value)
      {
        if (isset($variable)) $var = $variable.'['.$index.']';
        else                     $var = $index;

        if (is_array($value))
        {
          $this->requestLogCookie($value, $var);
        }
        else
        {
          self::$DL->RequestLogInsertCookie($this->rqlId, $var, $value);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the post variables in to the database.
   *
   * Usage on this method on production environments is not recommended.
   *
   * @param array       $post     Must be $_POST (except for recursive calls).
   * @param string|null $variable Must not be used (except for recursive calls).
   */
  private function requestLogPost($post, $variable = null)
  {
    if (is_array($post))
    {
      foreach ($post as $index => $value)
      {
        if (isset($variable)) $var = $variable.'['.$index.']';
        else                     $var = $index;

        if (is_array($value))
        {
          $this->requestLogPost($value, $var);
        }
        else
        {
          self::$DL->RequestLogInsertPost($this->rqlId, $var, $value);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the executed executed database queries.
   */
  private function requestLogQuery()
  {
    $queries = self::$DL->getQueryLog();

    foreach ($queries as $query)
    {
      self::$DL->requestLogInsertQuery($this->rqlId, $query['query'], $query['time']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the canonical server name (a.k.a. hostname). This canonical server name is derived from $_SERVER.
   */
  private function setCanonicalServerName()
  {
    if (!empty($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
      $list     = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
      $hostname = (end($list));
    }
    elseif (!empty($_SERVER['HTTP_HOST']))
    {
      $hostname = $_SERVER['HTTP_HOST'];
    }
    elseif (!empty($_SERVER['SERVER_NAME']))
    {
      $hostname = $_SERVER['SERVER_NAME'];
    }
    elseif (!empty($_SERVER['SERVER_ADDR']))
    {
      $hostname = $_SERVER['SERVER_ADDR'];
    }
    else
    {
      $hostname = '';
    }

    // Remove port number, if any.
    $p = strpos($hostname, ':');
    if ($p!==false) $hostname = substr($hostname, 0, $p);

    $this->canonicalServerName = strtolower(trim($hostname));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the domain (or Company abbreviation) based on the third level domain (TLD) name of the canonical host name.
   */
  private function setDomain()
  {
    // If possible derive domain from the canonical server name.
    $parts = explode('.', $this->canonicalServerName);
    if (count($parts)==3 && $parts[0]!='www')
    {
      $this->domain = strtoupper($parts[0]);
    }
    else
    {
      $this->domain = 'SYS';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the session in the DB.
   */
  private function updateSession()
  {
    self::$DL->sessionUpdate($this->getSesId());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
