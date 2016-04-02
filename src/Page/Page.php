<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Page;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\Page\Misc\W3cValidatePage;
use SetBased\Abc\Error\InvalidUrlException;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\Url;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for all pages.
 */
abstract class Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The last created instance this class.
   *
   * @var Page
   */
  private static $ourPage;

  /**
   * The Company id (cmp_id) of the page requester.
   *
   * @var int
   */
  protected $myCmpId;

  /**
   * CSS code to be included in the head of this page.
   */
  protected $myCss;

  /**
   * List with CSS sources to be included on this page.
   *
   * @var string[]
   */
  protected $myCssSources = [];

  /**
   * JavaScript code to be included in the head of this page.
   *
   * @var string
   */
  protected $myJavaScript;

  /**
   * The attributes of the script element in the page trailer (i.e. near the end html tag). Example:
   * ```
   * [ 'src' => '/js/requirejs.js', 'data-main' => '/js/main.js' ]
   * ```
   *
   * @var array
   */
  protected $myJsTrailerAttributes;

  /**
   * The keywords to be included in a meta tag for this page.
   *
   * var string[]
   */
  protected $myKeywords = [];

  /**
   * The preferred language (lan_id) of the page requester.
   *
   * @var int
   */
  protected $myLanId;

  /**
   * The profile ID (pro_id) of the page requestor.
   *
   * @var int
   */
  protected $myProId;

  /**
   * The user ID (usr_id) of the page requestor.
   *
   * @var int
   */
  protected $myUsrId;

  /**
   * The path where the HTML code of this page is stored for the W3C validator.
   *
   * @var string
   */
  protected $myW3cPathName;

  /**
   * If set to true (typically on DEV environment) the HTML code of this page will be validated by the W3C validator.
   *
   * @var bool
   */
  protected $myW3cValidate = false;

  /**
   * The size (in bytes) of the HTML code of this page.
   *
   * @var int
   */
  private $myPageSize = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $abc = Abc::getInstance();

    $this->myCmpId = $abc->getCmpId();
    $this->myProId = $abc->getProId();
    $this->myUsrId = $abc->getUsrId();
    $this->myLanId = $abc->getLanId();

    self::$ourPage = $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a class specific CCS file to the last created page.
   *
   * This method is a static wrapper around method {@link cssAppendPageSpecificSource}.
   *
   * @param string      $theClassName The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                                  slashes to construct the filename relative to the resource root of the CSS
   *                                  source.
   * @param string|null $theDevice    The device for which the CSS source is optimized for.
   *                                  * null       Suitable for all devices. null is preferred over 'all'.
   *                                  * aural      Speech synthesizers
   *                                  * braille    Braille feedback devices
   *                                  * handheld   Handheld devices (small screen, limited bandwidth)
   *                                  * projection Projectors
   *                                  * print      Print preview mode/printed pages
   *                                  * screen     Computer screens
   *                                  * tty        Teletypes and similar media using a fixed-pitch character grid
   *                                  * tv         Television type devices (low resolution, limited scroll ability)
   */
  public static function cssStaticAppendClassSource($theClassName, $theDevice = null)
  {
    self::$ourPage->cssAppendPageSpecificSource($theClassName, $theDevice);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a CCS file to the header to the last created page.
   *
   * This method is a static wrapper around method {@link cssAppendSource}.
   *
   * @param string      $theSource The filename relative to the resource root of the CSS source.
   * @param string|null $theDevice The device for which the CSS source is optimized for. Possible values:
   *                               * null       Suitable for all devices. null is preferred over 'all'.
   *                               * aural      Speech synthesizers
   *                               * braille    Braille feedback devices
   *                               * handheld   Handheld devices (small screen, limited bandwidth)
   *                               * projection Projectors
   *                               * print      Print preview mode/printed pages
   *                               * screen     Computer screens
   *                               * tty        Teletypes and similar media using a fixed-pitch character grid
   *                               * tv         Television type devices (low resolution, limited scroll ability)
   */
  public static function cssStaticAppendSource($theSource, $theDevice = null)
  {
    self::$ourPage->cssAppendSource($theSource, $theDevice);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an optimized CCS file to the header of last created page.
   *
   * Do not use this method directly. Use {@link cssAppendPageSpecificSource} instead.
   *
   * @param string      $theSource The filename relative to the resource root of the CSS source.
   * @param string|null $theDevice The device for which the CSS source is optimized for. Possible values:
   *                               * null       Suitable for all devices. null is preferred over 'all'.
   *                               * aural      Speech synthesizers
   *                               * braille    Braille feedback devices
   *                               * handheld   Handheld devices (small screen, limited bandwidth)
   *                               * projection Projectors
   *                               * print      Print preview mode/printed pages
   *                               * screen     Computer screens
   *                               * tty        Teletypes and similar media using a fixed-pitch character grid
   *                               * tv         Television type devices (low resolution, limited scroll ability)
   */
  public static function cssStaticOptimizedAppendSource($theSource, $theDevice = null)
  {
    self::$ourPage->myCssSources[] = ['href'  => $theSource,
                                      'media' => $theDevice,
                                      'rel'   => 'stylesheet',
                                      'type'  => 'text/css'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a boolean CGI variable.
   *
   * If bivalent is applied returns:
   * <ul>
   * <li>true if the CGI variable is set and is not empty
   * <li>false otherwise.
   * <ul>
   * If trinary logic is applied returns:
   * <ul>
   * <li>true if the CGI variable is set and is not empty
   * <li>false if the CGI variable is set and is empty
   * <li>null if the CGI variable not set.
   * <ul>
   *
   * @param string $theVarName     The name of the CGI variable.
   * @param bool   $theTrinaryFlag If true trinary (a.k.a  three-valued) logic will be applied. Otherwise, bivalent
   *                               logic will be applied.
   *
   * @return bool|null
   */
  public static function getCgiBool($theVarName, $theTrinaryFlag = false)
  {
    if (isset($_GET[$theVarName]))
    {
      return !empty($_GET[$theVarName]);
    }

    return ($theTrinaryFlag) ? null : false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an obfuscated database ID.
   *
   * @param string $theVarName The name of the CGI variable.
   * @param string $theLabel   An alias for the column holding database ID and must corresponds with label that was
   *                           used to obfuscate the database ID.
   *
   * @return int
   */
  public static function getCgiId($theVarName, $theLabel)
  {
    if (isset($_GET[$theVarName]))
    {
      return Abc::deObfuscate($_GET[$theVarName], $theLabel);
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the value of a CGI variable holding an URL.
   *
   * This method will protect against unvalidated redirects, see
   * <https://www.owasp.org/index.php/Unvalidated_Redirects_and_Forwards_Cheat_Sheet>.
   *
   * @param string $theVarName           The name of the CGI variable.
   * @param bool   $theForceRelativeFlag If set the URL must be a relative URL. If the URL is not a relative URL an
   *                                     exception will be thrown.
   *
   * @return string
   *
   * @throws InvalidUrlException
   */
  public static function getCgiUrl($theVarName, $theForceRelativeFlag = true)
  {
    if (isset($_GET[$theVarName]))
    {
      $url = urldecode($_GET[$theVarName]);
      if ($theForceRelativeFlag && !Url::isRelative($url))
      {
        throw new InvalidUrlException("Value '%s' of CGI variable '%s' is not a relative URL.", $url, $theVarName);
      }

      return $url;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a CGI variable.
   *
   * For retrieving a CGI variable with a relative URL use {@link getCgiUrl}.
   *
   * @param string $theVarName The name of the CGI variable.
   *
   * @return string
   */
  public static function getCgiVar($theVarName)
  {
    if (isset($_GET[$theVarName]))
    {
      return urlencode($_GET[$theVarName]);
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in the same namespace as the PHP class (where backslashes will be translated to
   * forward slashes). Example:
   * ```
   * $this->jsAdmPageSpecificFunctionCall(__CLASS__, 'init');
   * ```
   *
   * This method is a static wrapper around method {@link jsAdmPageSpecificFunctionCall}.
   *
   * @param string $theClassName      The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward
   *                                  slashes to construct the namespace.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  public static function jsAdmStaticClassSpecificFunctionCall($theClassName, $theJsFunctionName, $args = [])
  {
    self::$ourPage->jsAdmPageSpecificFunctionCall($theClassName, $theJsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in a namespace.
   *
   * This method is a static wrapper around method {@link jsAdmFunctionCall}.
   *
   * @param string $theNamespace      The namespace as in RequireJS.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  public static function jsAdmStaticFunctionCall($theNamespace, $theJsFunctionName, $args = [])
  {
    self::$ourPage->jsAdmFunctionCall($theNamespace, $theJsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Do not use this function, use {@link jsAdmFunctionCall} instead.
   *
   * @param string $theNamespace      The namespace as in RequireJS.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  public static function jsAdmStaticOptimizedFunctionCall($theNamespace, $theJsFunctionName, $args = [])
  {
    self::$ourPage->myJavaScript .= 'require(["';
    self::$ourPage->myJavaScript .= $theNamespace;
    self::$ourPage->myJavaScript .= '"],function(page){\'use strict\';page.';
    self::$ourPage->myJavaScript .= $theJsFunctionName;
    self::$ourPage->myJavaScript .= '(';
    self::$ourPage->myJavaScript .= implode(',', array_map('json_encode', $args));
    self::$ourPage->myJavaScript .= ');});';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with holding a boolean CGI variable that can be used as a part of a URL.
   *
   * If bivalent is applied returns:
   * <ul>
   * <li>a clean CGI variable set to 1 if the value of the CGI variable is set and is not empty,
   * <li>an empty string otherwise.
   * <ul>
   * If trinary logic is applied returns:
   * <ul>
   * <li>a clean CGI variable set to 1 if the value of the CGI variable is set and is not empty,
   * <li>a clean CGI variable set to 0 if the value of the CGI variable is set and is empty,
   * <li>an empty string otherwise.
   * <ul>
   *
   * @param string $theVarName     The name of the boolean CGI variable.
   * @param mixed  $theValue       The value of the CGI variable. Only and only a nonempty value evaluates to true.
   * @param bool   $theTrinaryFlag If true trinary (a.k.a  three-valued) logic will be applied. Otherwise, bivalent
   *                               logic will be applied.
   *
   * @return string
   */
  public static function putCgiBool($theVarName, $theValue, $theTrinaryFlag = false)
  {
    if (!empty($theValue))
    {
      return '/'.$theVarName.'/1';
    }

    if ($theTrinaryFlag && isset($theValue))
    {
      return '/'.$theVarName.'/0';
    }

    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with holding a CGI variable that can be used as a part of a URL.
   *
   * @param string $theVarName The name of the CGI variable.
   * @param mixed  $theValue   The value (must be a scalar) of the CGI variable.
   * @param string $theLabel   Must only be used if the CGI variable is a database ID. An alias for the column holding
   *                           database ID.
   *
   * @return string
   */
  public static function putCgiVar($theVarName, $theValue, $theLabel = null)
  {
    if (isset($theValue))
    {
      if (isset($theLabel))
      {
        return '/'.$theVarName.'/'.Abc::obfuscate($theValue, $theLabel);
      }

      return '/'.$theVarName.'/'.urlencode($theValue);
    }

    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If a page needs additional and page specific authorization and/or security checks this method must be overridden.
   *
   * When a HTTP request must be denied a NotAuthorizedException must be raised.
   */
  public function checkAuthorization()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented in child classes to echo the actual page content, i.e. the inner HTML of the body tag.
   *
   * @return null
   */
  abstract public function echoPage();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the size (in byes) of the HTML code of this page.
   *
   * @return int
   */
  public function getPageSize()
  {
    return $this->myPageSize;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If this page can be requested via multiple URL and one URL is preferred this method must be overridden to return
   * the preferred URI of this page.
   *
   * Typically this method will be used when the URL contains some ID and an additional title.
   * Example:
   * Initially a page with an article about a book is created with title "Harry Potter and the Sorcerer's Stone" and the
   * URI is /book/123456/Harry_Potter_and_the_Sorcerer's_Stone.html. After this article has been edited the URI is
   * /book/123456/Harry_Potter_and_the_Philosopher's_Stone.html. The later URI is the preferred URI now.
   *
   * If the preferred URI is set and different from the requested URI the user agent will be redirected to the
   * preferred URI with HTTP status code 301 (Moved Permanently).
   *
   * @return string|null The preferred URI of this page.
   */
  public function getPreferredUri()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a word to the key words to be included in a meta tag for this page.
   *
   * @param string $theKeyword The keyword.
   */
  protected function addKeyword($theKeyword)
  {
    $this->myKeywords[] = $theKeyword;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds words to the key words to be included in a meta tag for this page.
   *
   * @param string[] $theKeywords The keywords.
   */
  protected function addKeywords($theKeywords)
  {
    $this->myKeywords = array_merge($this->myKeywords, $theKeywords);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Appends with a separator a string to the page title
   *
   * @param string $thePageTitleAddendum The text to append to the page title.
   */
  protected function appendPageTitle($thePageTitleAddendum)
  {
    Abc::getInstance()->appendPageTitle($thePageTitleAddendum);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a page specific CCS file to the header of this page.
   *
   * @param string      $theClassName The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                                  slashes to construct the filename relative to the resource root of the CSS
   *                                  source.
   * @param string|null $theDevice    The device for which the CSS source is optimized for.
   *                                  * null       Suitable for all devices. null is preferred over 'all'.
   *                                  * aural      Speech synthesizers
   *                                  * braille    Braille feedback devices
   *                                  * handheld   Handheld devices (small screen, limited bandwidth)
   *                                  * projection Projectors
   *                                  * print      Print preview mode/printed pages
   *                                  * screen     Computer screens
   *                                  * tty        Teletypes and similar media using a fixed-pitch character grid
   *                                  * tv         Television type devices (low resolution, limited scroll ability)
   */
  protected function cssAppendPageSpecificSource($theClassName, $theDevice = null)
  {
    // Construct the filename of the CSS file.
    $filename = '/css/'.str_replace('\\', '/', $theClassName);
    if (isset($theDevice)) $filename .= '.'.$theDevice;
    $filename .= '.css';

    $this->cssAppendSource($filename, $theDevice);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a CCS file to the header of this page.
   *
   * @param string      $theSource The filename relative to the resource root of the CSS source.
   * @param string|null $theDevice The device for which the CSS source is optimized for. Possible values:
   *                               * null       Suitable for all devices. null is preferred over 'all'.
   *                               * aural      Speech synthesizers
   *                               * braille    Braille feedback devices
   *                               * handheld   Handheld devices (small screen, limited bandwidth)
   *                               * projection Projectors
   *                               * print      Print preview mode/printed pages
   *                               * screen     Computer screens
   *                               * tty        Teletypes and similar media using a fixed-pitch character grid
   *                               * tv         Television type devices (low resolution, limited scroll ability)
   */
  protected function cssAppendSource($theSource, $theDevice = null)
  {
    $path = HOME.'/www'.$theSource;
    if (!file_exists($path))
    {
      throw new LogicException("CSS file '%s' does not exists.", $theSource);
    }

    $this->cssOptimizedAppendSource($theSource, $theDevice);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an optimized CCS file to the header of this page.
   *
   * Do not use this method directly. Use {@link cssAppendPageSpecificSource} instead.
   *
   * @param string      $theSource The filename relative to the resource root of the CSS source.
   * @param string|null $theDevice The device for which the CSS source is optimized for. Possible values:
   *                               * null       Suitable for all devices. null is preferred over 'all'.
   *                               * aural      Speech synthesizers
   *                               * braille    Braille feedback devices
   *                               * handheld   Handheld devices (small screen, limited bandwidth)
   *                               * projection Projectors
   *                               * print      Print preview mode/printed pages
   *                               * screen     Computer screens
   *                               * tty        Teletypes and similar media using a fixed-pitch character grid
   *                               * tv         Television type devices (low resolution, limited scroll ability)
   */
  protected function cssOptimizedAppendSource($theSource, $theDevice = null)
  {
    $this->myCssSources[] = ['href'  => $theSource,
                             'media' => $theDevice,
                             'rel'   => 'stylesheet',
                             'type'  => 'text/css'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the links to external style sheets and internal style sheet.
   */
  protected function echoCascadingStyleSheets()
  {
    // Echo links to external style sheets.
    foreach ($this->myCssSources as $css_source)
    {
      echo Html::generateVoidElement('link', $css_source);
    }

    // Echo the internal style sheet.
    if ($this->myCss)
    {
      echo '<style type="text/css" media="all">', $this->myCss, '</style>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only and only if there are keywords for this page echos the keywords meta tag.
   */
  protected function echoMetaTagKeywords()
  {
    if (!empty($this->myKeywords))
    {
      echo '<meta name="keywords"', Html::generateAttribute('content', implode(',', $this->myKeywords)), '/>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the meta tags within the HTML document.
   */
  protected function echoMetaTags()
  {
    // Echo meta tag that specifies the character encoding.
    echo '<meta', Html::generateAttribute('charset', Html::$ourEncoding), '/>';

    // Echo meta tag for keywords (if any).
    $this->echoMetaTagKeywords();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document leader, i.e. the start html tag, the head element, and start body tag.
   */
  protected function echoPageLeader()
  {
    $lan_code = Abc::getInstance()->getLanCode();
    echo '<!DOCTYPE html>';
    echo '<html xmlns="http://www.w3.org/1999/xhtml"', Html::generateAttribute('xml:lang', $lan_code),
    Html::generateAttribute('lang', $lan_code), '>';
    echo '<head>';

    // Echo the meta tags.
    $this->echoMetaTags();

    // Echo the title of the XHTML document.
    echo '<title>', Html::txt2Html(Abc::getInstance()->getPageTitle()), '</title>';

    // Echo style sheets (if any).
    $this->echoCascadingStyleSheets();

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document trailer, i.e. the end body and html tags, including the JavaScript code that will be
   * executed using RequireJS.
   */
  protected function echoPageTrailer()
  {
    if ($this->myJavaScript)
    {
      $js = 'require([],function(){'.$this->myJavaScript.'});';
      echo '<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js='.json_encode($js).'/*]]>*/</script>';
    }
    if (!empty($this->myJsTrailerAttributes))
    {
      echo Html::generateElement('script', $this->myJsTrailerAttributes);
    }

    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables validation of the HTML code of this page by the W3C Validator.
   */
  protected function enableW3cValidator()
  {
    $prefix              = 'w3c_validator_'.Abc::obfuscate($this->myUsrId, 'usr').'_';
    $w3c_file            = uniqid($prefix).'.xhtml';
    $this->myW3cValidate = true;
    $this->myW3cPathName = DIR_TMP.'/'.$w3c_file;
    $url                 = W3cValidatePage::getUrl($w3c_file);
    $this->jsAdmPageSpecificFunctionCall(__CLASS__, 'w3cValidate', [$url]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getPagIdOrg()
  {
    return Abc::getInstance()->getPagIdOrg();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the title of this page.
   *
   * @return string
   */
  protected function getPageTitle()
  {
    return Abc::getInstance()->getPageTitle();
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getPtbId()
  {
    return Abc::getInstance()->getPtbId();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in a namespace.
   *
   * @param string $theNamespace      The namespace as in RequireJS.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  protected function jsAdmFunctionCall($theNamespace, $theJsFunctionName, $args = [])
  {
    // Construct the filename of the JS file.
    $filename = '/js/'.$theNamespace.'.js';

    // Test JS file actually exists.
    $path = HOME.'/www'.$filename;
    if (!file_exists($path))
    {
      throw new LogicException("JavaScript file '%s' does not exists.", $filename);
    }

    $this->jsAdmOptimizedFunctionCall($theNamespace, $theJsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Do not use this function, use {@link jsAdmFunctionCall} instead.
   *
   * @param string $theNamespace      The namespace as in RequireJS.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  protected function jsAdmOptimizedFunctionCall($theNamespace, $theJsFunctionName, $args = [])
  {
    $this->myJavaScript .= 'require(["';
    $this->myJavaScript .= $theNamespace;
    $this->myJavaScript .= '"],function(page){\'use strict\';page.';
    $this->myJavaScript .= $theJsFunctionName;
    $this->myJavaScript .= '(';
    $this->myJavaScript .= implode(',', array_map('json_encode', $args));
    $this->myJavaScript .= ');});';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Do not use this function, use {@link jsAdmFunctionCall} instead.
   * ```
   * $this->jsAdmSetPageSpecificMain(__CLASS__);
   * ```
   *
   * @param string $theMainJsScript The main script for RequireJS.
   */
  protected function jsAdmOptimizedSetPageSpecificMain($theMainJsScript)
  {
    $this->myJsTrailerAttributes = ['src' => $theMainJsScript];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in the same namespace as the PHP class (where backslashes will be translated to
   * forward slashes). Example:
   * ```
   * $this->jsAdmPageSpecificFunctionCall(__CLASS__, 'init');
   * ```
   *
   * @param string $theClassName      The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward
   *                                  slashes to construct the namespace.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  protected function jsAdmPageSpecificFunctionCall($theClassName, $theJsFunctionName, $args = [])
  {
    // Convert PHP class name to RequireJS namespace name.
    $namespace = str_replace('\\', '/', $theClassName);

    $this->jsAdmFunctionCall($namespace, $theJsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a page specific main for RequireJS. Example:
   * ```
   * $this->jsAdmSetPageSpecificMain(__CLASS__);
   * ```
   *
   * @param string $theClassName The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward
   *                             slashes to construct the namespace.
   */
  protected function jsAdmSetPageSpecificMain($theClassName)
  {
    // Convert PHP class name to RequireJS namespace name.
    $namespace = str_replace('\\', '/', $theClassName);

    // Construct the filename of the JS file.
    $filename = '/js/'.$namespace.'.main.js';

    // Test JS file actually exists.
    $path = HOME.'/www'.$filename;
    if (!file_exists($path))
    {
      throw new \LogicException(sprintf("JavaScript file '%s' does not exists.", $filename));
    }

    $this->myJsTrailerAttributes = ['src' => '/js/require.js', 'data-main' => $filename];
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function setPageSize($theSize)
  {
    $this->myPageSize = $theSize;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title for current page.
   *
   * @param string $thePageTitle The new title of the page.
   */
  protected function setPageTitle($thePageTitle)
  {
    Abc::getInstance()->setPageTitle($thePageTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
