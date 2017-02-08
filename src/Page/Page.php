<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Page;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\Page\Misc\W3cValidatePage;
use SetBased\Abc\Error\InvalidUrlException;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\Url;
use SetBased\Exception\LogicException;

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
  private static $page;

  /**
   * The ID of the company of the page requester.
   *
   * @var int
   */
  protected $cmpId;

  /**
   * CSS code to be included in the head of this page.
   */
  protected $css;

  /**
   * List with CSS sources to be included on this page.
   *
   * @var array[]
   */
  protected $cssSources = [];

  /**
   * JavaScript code to be included in the head of this page.
   *
   * @var string
   */
  protected $javaScript;

  /**
   * The attributes of the script element in the page trailer (i.e. near the end html tag). Example:
   * ```
   * [ 'src' => '/js/requirejs.js', 'data-main' => '/js/main.js' ]
   * ```
   *
   * @var array
   */
  protected $jsTrailerAttributes;

  /**
   * The keywords to be included in a meta tag for this page.
   *
   * var string[]
   */
  protected $keywords = [];

  /**
   * The preferred language (lan_id) of the page requester.
   *
   * @var int
   */
  protected $lanId;

  /**
   * The attributes of the meta elements of this page.
   *
   * @var array[]
   */
  protected $metaAttributes = [];

  /**
   * The profile ID (pro_id) of the page requestor.
   *
   * @var int
   */
  protected $proId;

  /**
   * The user ID (usr_id) of the page requestor.
   *
   * @var int
   */
  protected $usrId;

  /**
   * The path where the HTML code of this page is stored for the W3C validator.
   *
   * @var string
   */
  protected $w3cPathName;

  /**
   * If set to true (typically on DEV environment) the HTML code of this page will be validated by the W3C validator.
   *
   * @var bool
   */
  protected $w3cValidate = false;

  /**
   * The size (in bytes) of the HTML code of this page.
   *
   * @var int
   */
  private $pageSize = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @api
   * @since 1.0.0
   */
  public function __construct()
  {
    $abc = Abc::getInstance();

    $this->cmpId = $abc->getCmpId();
    $this->proId = $abc->getProId();
    $this->usrId = $abc->getUsrId();
    $this->lanId = $abc->getLanId();

    self::$page = $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a class specific CCS file to the last created page.
   *
   * This method is a static wrapper around method {@link cssAppendPageSpecificSource}.
   *
   * @param string      $className The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                               slashes to construct the filename relative to the resource root of the CSS
   *                               source.
   * @param string|null $media     The media for which the CSS source is optimized for. Note: use null for 'all'
   *                               devices; null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  public static function cssStaticAppendClassSource($className, $media = null)
  {
    self::$page->cssAppendPageSpecificSource($className, $media);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a CCS file to the header to the last created page.
   *
   * This method is a static wrapper around method {@link cssAppendSource}.
   *
   * @param string      $source The filename relative to the resource root of the CSS source.
   * @param string|null $media  The media for which the CSS source is optimized for. Note: use null for 'all' devices;
   *                            null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  public static function cssStaticAppendSource($source, $media = null)
  {
    self::$page->cssAppendSource($source, $media);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an optimized CCS file to the header of last created page.
   *
   * Do not use this method directly. Use {@link cssAppendPageSpecificSource} instead.
   *
   * @param string      $source The filename relative to the resource root of the CSS source.
   * @param string|null $media  The media for which the CSS source is optimized for. Note: use null for 'all' devices;
   *                            null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  public static function cssStaticOptimizedAppendSource($source, $media = null)
  {
    self::$page->cssSources[] = ['href'  => $source,
                                 'media' => $media,
                                 'rel'   => 'stylesheet',
                                 'type'  => 'text/css'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a boolean CGI variable.
   *
   * If bivalent is applied returns:
   * <ul>
   *   <li>true if the CGI variable is set and is not empty
   *   <li>false otherwise.
   * </ul>
   * If trinary logic is applied returns:
   * <ul>
   *   <li>true if the CGI variable is set and is not empty
   *   <li>false if the CGI variable is set and is empty
   *   <li>null if the CGI variable not set.
   * </ul>
   *
   * @param string $name    The name of the CGI variable.
   * @param bool   $trinary If true trinary (a.k.a  three-valued) logic will be applied. Otherwise, bivalent logic will
   *                        be applied.
   *
   * @return bool|null
   *
   * @api
   * @since 1.0.0
   */
  public static function getCgiBool($name, $trinary = false)
  {
    if (isset($_GET[$name]))
    {
      return !empty($_GET[$name]);
    }

    return ($trinary) ? null : false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an obfuscated database ID.
   *
   * @param string   $name    The name of the CGI variable.
   * @param string   $label   An alias for the column holding database ID and must corresponds with label that was used
   *                          to obfuscate the database ID.
   * @param int|null $default The value to be used when the CGI variable is not set.
   *
   * @return int|null
   * @api
   * @since 1.0.0
   */
  public static function getCgiId($name, $label, $default = null)
  {
    if (isset($_GET[$name]))
    {
      return Abc::deObfuscate($_GET[$name], $label);
    }

    return $default;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the value of a CGI variable holding an URL.
   *
   * This method will protect against unvalidated redirects, see
   * <https://www.owasp.org/index.php/Unvalidated_Redirects_and_Forwards_Cheat_Sheet>.
   *
   * @param string      $name          The name of the CGI variable.
   * @param string|null $default       The URL to be used when the CGI variable is not set.
   * @param bool        $forceRelative If set the URL must be a relative URL. If the URL is not a relative URL an
   *                                   exception will be thrown.
   *
   * @return string|null
   *
   * @throws InvalidUrlException
   *
   * @api
   * @since 1.0.0
   */
  public static function getCgiUrl($name, $default = null, $forceRelative = true)
  {
    $url = (isset($_GET[$name])) ? urldecode($_GET[$name]) : $default;

    if ($forceRelative && $url!==null && !Url::isRelative($url))
    {
      throw new InvalidUrlException("Value '%s' of CGI variable '%s' is not a relative URL", $url, $name);
    }

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a CGI variable.
   *
   * For retrieving a CGI variable with a relative URI use {@link getCgiUrl}.
   *
   * @param string      $name    The name of the CGI variable.
   * @param string|null $default The value to be used when the CGI variable is not set.
   *
   * @return string|null
   *
   * @api
   * @since 1.0.0
   */
  public static function getCgiVar($name, $default = null)
  {
    if (isset($_GET[$name]))
    {
      return urldecode($_GET[$name]);
    }

    return $default;
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
   * @param string $className      The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward
   *                               slashes to construct the namespace.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   *
   * @api
   * @since 1.0.0
   */
  public static function jsAdmStaticClassSpecificFunctionCall($className, $jsFunctionName, $args = [])
  {
    self::$page->jsAdmPageSpecificFunctionCall($className, $jsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in a namespace.
   *
   * This method is a static wrapper around method {@link jsAdmFunctionCall}.
   *
   * @param string $namespace      The namespace as in RequireJS.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   *
   * @api
   * @since 1.0.0
   */
  public static function jsAdmStaticFunctionCall($namespace, $jsFunctionName, $args = [])
  {
    self::$page->jsAdmFunctionCall($namespace, $jsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Do not use this function, use {@link jsAdmFunctionCall} instead.
   *
   * @param string $namespace      The namespace as in RequireJS.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   */
  public static function jsAdmStaticOptimizedFunctionCall($namespace, $jsFunctionName, $args = [])
  {
    self::$page->javaScript .= 'require(["';
    self::$page->javaScript .= $namespace;
    self::$page->javaScript .= '"],function(page){\'use strict\';page.';
    self::$page->javaScript .= $jsFunctionName;
    self::$page->javaScript .= '(';
    self::$page->javaScript .= implode(',', array_map('json_encode', $args));
    self::$page->javaScript .= ');});';
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
   * @param string $name    The name of the boolean CGI variable.
   * @param mixed  $value   The value of the CGI variable. Only and only a nonempty value evaluates to true.
   * @param bool   $trinary If true trinary (a.k.a  three-valued) logic will be applied. Otherwise, bivalent logic will
   *                        be applied.
   *
   * @return string
   *
   * @api
   * @since 1.0.0
   */
  public static function putCgiBool($name, $value, $trinary = false)
  {
    if (!empty($value))
    {
      return '/'.$name.'/1';
    }

    if ($trinary && $value!==null)
    {
      return '/'.$name.'/0';
    }

    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with holding a CGI variable that can be used as a part of a URL.
   *
   * @param string      $name  The name of the CGI variable.
   * @param mixed       $value The value (must be a scalar) of the CGI variable.
   * @param string|null $label The alias for the column holding database ID.
   *
   * @return string
   *
   * @api
   * @since 1.0.0
   */
  public static function putCgiId($name, $value, $label)
  {
    if ($value!==null)
    {
      return '/'.$name.'/'.Abc::obfuscate($value, $label);
    }

    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns (virtual) filename based on the slug of a string that can be safely used in an URL.
   *
   * @param string $string    The string.
   * @param string $extension The extension of the (virtual) filename.
   *
   * @return string
   */
  public static function putCgiSlugName($string, $extension = '.html')
  {
    $slug = Html::txt2Slug($string);

    return ($slug==='') ? '' : '/'.$slug.$extension;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with holding a CGI variable with an URL as value that can be used as a part of a URL.
   *
   * Note: This method is an alias of {@link putCgiVar}.
   *
   * @param string      $name  The name of the CGI variable.
   * @param string|null $value The value of the CGI variable.
   *
   * @return string
   *
   * @api
   * @since 1.0.0
   */
  public static function putCgiUrl($name, $value)
  {
    return self::putCgiVar($name, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with holding a CGI variable that can be used as a part of a URL.
   *
   * @param string $name  The name of the CGI variable.
   * @param mixed  $value The value (must be a scalar) of the CGI variable.
   *
   * @return string
   *
   * @api
   * @since 1.0.0
   */
  public static function putCgiVar($name, $value)
  {
    return ($value!==null) ? '/'.$name.'/'.urlencode($value) : '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If a page needs additional and page specific authorization and/or security checks this method must be overridden.
   *
   * When a HTTP request must be denied a NotAuthorizedException must be raised.
   *
   * @api
   * @since 1.0.0
   */
  public function checkAuthorization()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented in child classes to echo the actual page content, i.e. the inner HTML of the body tag.
   *
   * @return void
   *
   * @api
   * @since 1.0.0
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
    return $this->pageSize;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If this page can be requested via multiple URI's and one URI is preferred this method must be overridden to return
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
   *
   * @api
   * @since 1.0.0
   */
  public function getPreferredUri()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a meta element to this page.
   *
   * @param array $attributes The attributes of the meta element.
   *
   * @api
   * @since 1.0.0
   */
  public function metaAddElement($attributes)
  {
    $this->metaAttributes[] = $attributes;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a keyword to the keywords to be included in the keyword meta element of this page.
   *
   * @param string $keyword The keyword.
   *
   * @api
   * @since 1.0.0
   */
  public function metaAddKeyword($keyword)
  {
    $this->keywords[] = $keyword;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds keywords to the keywords to be included in the keyword meta element of this page.
   *
   * @param string[] $keywords The keywords.
   *
   * @api
   * @since 1.0.0
   */
  public function metaAddKeywords($keywords)
  {
    $this->keywords = array_merge($this->keywords, $keywords);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Appends with a separator a string to the page title
   *
   * @param string $pageTitleAddendum The text to append to the page title.
   *
   * @api
   * @since 1.0.0
   */
  protected function appendPageTitle($pageTitleAddendum)
  {
    Abc::getInstance()->appendPageTitle($pageTitleAddendum);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a page specific CCS file to the header of this page.
   *
   * @param string      $className The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                               slashes to construct the filename relative to the resource root of the CSS source.
   * @param string|null $media     The media for which the CSS source is optimized for. Note: use null for 'all'
   *                               devices; null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  protected function cssAppendPageSpecificSource($className, $media = null)
  {
    // Construct the filename of the CSS file.
    $filename = '/css/'.str_replace('\\', '/', $className);
    if ($media!==null) $filename .= '.'.$media;
    $filename .= '.css';

    $this->cssAppendSource($filename, $media);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a CCS file to the header of this page.
   *
   * @param string      $source The filename relative to the resource root of the CSS source.
   * @param string|null $media  The media for which the CSS source is optimized for. Note: use null for 'all' devices;
   *                            null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  protected function cssAppendSource($source, $media = null)
  {
    $path = HOME.'/www'.$source;
    if (!file_exists($path))
    {
      throw new LogicException("CSS file '%s' does not exists.", $source);
    }

    $this->cssOptimizedAppendSource($source, $media);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an optimized CCS file to the header of this page.
   *
   * Do not use this method directly. Use {@link cssAppendPageSpecificSource} instead.
   *
   * @param string      $source The filename relative to the resource root of the CSS source.
   * @param string|null $media  The media for which the CSS source is optimized for. Note: use null for 'all' devices;
   *                            null is preferred over 'all'.
   */
  protected function cssOptimizedAppendSource($source, $media = null)
  {
    $this->cssSources[] = ['href'  => $source,
                           'media' => $media,
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
    foreach ($this->cssSources as $css_source)
    {
      echo Html::generateVoidElement('link', $css_source);
    }

    // Echo the internal style sheet.
    if ($this->css)
    {
      echo '<style type="text/css" media="all">', $this->css, '</style>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the meta tags within the HTML document.
   */
  protected function echoMetaTags()
  {
    if (!empty($this->keywords))
    {
      $this->metaAttributes[] = ['name' => 'keywords', 'content' => implode(',', $this->keywords)];
    }

    $this->metaAttributes[] = ['charset' => Html::$encoding];

    foreach ($this->metaAttributes as $metaAttribute)
    {
      echo Html::generateVoidElement('meta', $metaAttribute);
    }
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
    if ($this->javaScript)
    {
      $js = 'require([],function(){'.$this->javaScript.'});';
      echo '<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js='.json_encode($js).'/*]]>*/</script>';
    }
    if (!empty($this->jsTrailerAttributes))
    {
      echo Html::generateElement('script', $this->jsTrailerAttributes);
    }

    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables validation of the HTML code of this page by the W3C Validator.
   */
  protected function enableW3cValidator()
  {
    $prefix            = 'w3c_validator_'.Abc::obfuscate($this->usrId, 'usr').'_';
    $w3c_file          = uniqid($prefix).'.xhtml';
    $this->w3cValidate = true;
    $this->w3cPathName = DIR_TMP.'/'.$w3c_file;
    $url               = W3cValidatePage::getUrl($w3c_file);
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
   * @param string $namespace      The namespace as in RequireJS.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   *
   * @api
   * @since 1.0.0
   */
  protected function jsAdmFunctionCall($namespace, $jsFunctionName, $args = [])
  {
    // Construct the filename of the JS file.
    $filename = '/js/'.$namespace.'.js';

    // Test JS file actually exists.
    $path = HOME.'/www'.$filename;
    if (!file_exists($path))
    {
      throw new LogicException("JavaScript file '%s' does not exists.", $filename);
    }

    $this->jsAdmOptimizedFunctionCall($namespace, $jsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Do not use this function, use {@link jsAdmFunctionCall} instead.
   *
   * @param string $namespace      The namespace as in RequireJS.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   */
  protected function jsAdmOptimizedFunctionCall($namespace, $jsFunctionName, $args = [])
  {
    $this->javaScript .= 'require(["';
    $this->javaScript .= $namespace;
    $this->javaScript .= '"],function(page){\'use strict\';page.';
    $this->javaScript .= $jsFunctionName;
    $this->javaScript .= '(';
    $this->javaScript .= implode(',', array_map('json_encode', $args));
    $this->javaScript .= ');});';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Do not use this function, use {@link jsAdmFunctionCall} instead.
   * ```
   * $this->jsAdmSetPageSpecificMain(__CLASS__);
   * ```
   *
   * @param string $mainJsScript The main script for RequireJS.
   */
  protected function jsAdmOptimizedSetPageSpecificMain($mainJsScript)
  {
    $this->jsTrailerAttributes = ['src' => $mainJsScript];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in the same namespace as the PHP class (where backslashes will be translated to
   * forward slashes). Example:
   * ```
   * $this->jsAdmPageSpecificFunctionCall(__CLASS__, 'init');
   * ```
   *
   * @param string $className      The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward slashes
   *                               to construct the namespace.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   *
   * @api
   * @since 1.0.0
   */
  protected function jsAdmPageSpecificFunctionCall($className, $jsFunctionName, $args = [])
  {
    // Convert PHP class name to RequireJS namespace name.
    $namespace = str_replace('\\', '/', $className);

    $this->jsAdmFunctionCall($namespace, $jsFunctionName, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a page specific main for RequireJS. Example:
   * ```
   * $this->jsAdmSetPageSpecificMain(__CLASS__);
   * ```
   *
   * @param string $className The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward slashes to
   *                          construct the namespace.
   *
   * @api
   * @since 1.0.0
   */
  protected function jsAdmSetPageSpecificMain($className)
  {
    // Convert PHP class name to RequireJS namespace name.
    $namespace = str_replace('\\', '/', $className);

    // Construct the filename of the JS file.
    $filename = '/js/'.$namespace.'.main.js';

    // Test JS file actually exists.
    $path = HOME.'/www'.$filename;
    if (!file_exists($path))
    {
      throw new \LogicException(sprintf("JavaScript file '%s' does not exists.", $filename));
    }

    $this->jsTrailerAttributes = ['src' => '/js/require.js', 'data-main' => $filename];
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function setPageSize($size)
  {
    $this->pageSize = $size;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title for current page.
   *
   * @param string $pageTitle The new title of the page.
   */
  protected function setPageTitle($pageTitle)
  {
    Abc::getInstance()->setPageTitle($pageTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
