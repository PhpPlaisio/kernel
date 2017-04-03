<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Helper;

use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Helper class for setting web assets (things like CSS, JavaScript and image files) and generating HTML code for
 * including web assets.
 */
class WebAssets
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The full path to the directory with web assets. Note: Must note have a trailing slash.
   *
   * @var string
   */
  public static $assetDir;

  /**
   * The root-relative URL for storing CSS files. Note: Must have a leading and a trailing slash.
   *
   * @var string
   */
  public static $cssRootRelativeUrl = '/css/';

  /**
   * The root-relative URL for storing JavaScript files. Note: Must have a leading and a trailing slash.
   *
   * @var string
   */
  public static $jsRootRelativeUrl = '/js/';

  /**
   * CSS code to be included on the page.
   *
   * @var string[]
   */
  protected $css = [];

  /**
   * List with CSS sources to be included on the page.
   *
   * @var array[]
   */
  protected $cssSources = [];

  /**
   * JavaScript code to be included on the page.
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

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a class specific CCS file to the page.
   *
   * @param string      $className The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                               slashes to construct the filename relative to the resource root of the CSS source.
   * @param string|null $media     The media for which the CSS source is optimized for. Note: use null for 'all'
   *                               devices; null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  public function cssAppendClassSpecificSource($className, $media = null)
  {
    $this->cssAppendSource($this->cssClassNameToRootRelativeUrl($className, $media), $media);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a line with a CSS snippet to the internal CSS.
   *
   * @param string $cssLine The line with CSS snippet.
   *
   * @api
   * @since 1.0.0
   */
  public function cssAppendLine($cssLine)
  {
    $this->css[] = $cssLine;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a CCS file to the header to the page.
   *
   * @param string      $url   The URL to the CSS source.
   * @param string|null $media The media for which the CSS source is optimized for. Note: use null for 'all' devices;
   *                           null is preferred over 'all'.
   *
   * @api
   * @since 1.0.0
   */
  public function cssAppendSource($url, $media = null)
  {
    $url = Url::combine(self::$cssRootRelativeUrl, $url);

    if (Url::isRelative($url))
    {
      $fullPath = $this->rootRelativeUrlToFullPath($url);
      if (!file_exists($fullPath))
      {
        throw new LogicException("CSS file '%s' does not exists", $fullPath);
      }
    }

    $this->cssOptimizedAppendSource($url, $media);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for a class specfic CSS file.
   *
   * @param string      $className The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                               slashes to construct the filename relative to the resource root of the CSS source.
   * @param string|null $media     The media for which the CSS source is optimized for. Note: use null for 'all'
   *                               devices; null is preferred over 'all'.
   *
   * @return string
   */
  public function cssClassNameToRootRelativeUrl($className, $media = null)
  {
    $url = self::$cssRootRelativeUrl.$this->jsClassNameToNamespace($className);
    if ($media!==null) $url .= '.'.$media;
    $url .= '.css';

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an optimized CCS file to the page.
   *
   * Do not use this method directly. Use {@link cssAppendPageSpecificSource} instead.
   *
   * @param string      $url   The URL to the CSS source.
   * @param string|null $media The media for which the CSS source is optimized for. Note: use null for 'all'
   *                           devices; null is preferred over 'all'.
   */
  public function cssOptimizedAppendSource($url, $media = null)
  {
    $this->cssSources[] = ['href'  => $url,
                           'media' => $media,
                           'rel'   => 'stylesheet',
                           'type'  => 'text/css'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the links to external and internal CSS.
   *
   * @api
   * @since 1.0.0
   */
  public function echoCascadingStyleSheets()
  {
    // Echo links to external CSS.
    foreach ($this->cssSources as $css_source)
    {
      echo Html::generateVoidElement('link', $css_source);
    }

    // Echos internal CSS.
    if (!empty($this->css))
    {
      echo '<style type="text/css" media="all">', implode('', $this->css), '</style>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos JavaScript code that will be executed using RequireJS.
   *
   * @api
   * @since 1.0.0
   */
  public function echoJavaScript()
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
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in the same namespace as the PHP class (where backslashes will be translated to
   * forward slashes). Example:
   * ```
   * $this->jsAdmPageSpecificFunctionCall(__CLASS__, 'init');
   * ```
   *
   * @param string $className      The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward slashes
   *                               to construct the namespace.
   * @param string $jsFunctionName The function name inside the namespace.
   * @param array  $args           The optional arguments for the function.
   *
   * @api
   * @since 1.0.0
   */
  public function jsAdmClassSpecificFunctionCall($className, $jsFunctionName, $args = [])
  {
    $this->jsAdmFunctionCall($this->jsClassNameToNamespace($className), $jsFunctionName, $args);
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
  public function jsAdmFunctionCall($namespace, $jsFunctionName, $args = [])
  {
    // Test JS file actually exists.
    $fullPath = $this->rootRelativeUrlToFullPath($this->jsNamespaceToRootRelativeUrl($namespace));
    if (!file_exists($fullPath))
    {
      throw new LogicException("JavaScript file '%s' does not exists", $fullPath);
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
  public function jsAdmOptimizedFunctionCall($namespace, $jsFunctionName, $args = [])
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
   * Do not use this function, use {@link jsAdmSetPageSpecificMain} instead.
   * ```
   * $this->jsAdmSetPageSpecificMain(__CLASS__);
   * ```
   *
   * @param string $mainJsScript The main script for RequireJS.
   */
  public function jsAdmOptimizedSetPageSpecificMain($mainJsScript)
  {
    $this->jsTrailerAttributes = ['src' => $mainJsScript];
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
  public function jsAdmSetPageSpecificMain($className)
  {
    // Convert PHP class name to root relative URL.
    $url = $this->jsClassNameToMainRootRelativeUrl($className);

    // Test JS file actually exists.
    $fullPath = $this->rootRelativeUrlToFullPath($url);
    if (!file_exists($fullPath))
    {
      throw new LogicException("JavaScript file '%s' does not exists", $fullPath);
    }

    $this->jsTrailerAttributes = ['src' => $this->jsNamespaceToRootRelativeUrl('require'), 'data-main' => $url];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for a class specfic main JS file.
   *
   * @param string $className The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                          slashes to construct the relative URL to the JS source.
   *
   * @return string
   */
  protected function jsClassNameToMainRootRelativeUrl($className)
  {
    return self::$jsRootRelativeUrl.$this->jsClassNameToNamespace($className).'.main.js';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the namespace that corresponds with a class name.
   *
   * @param string $className The class name.
   *
   * @returns string
   */
  protected function jsClassNameToNamespace($className)
  {
    return str_replace('\\', '/', $className);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the root-relative URL for a class specific CSS file.
   *
   * @param string $namespace The namespace as in RequireJS.
   *
   * @return string
   */
  protected function jsNamespaceToRootRelativeUrl($namespace)
  {
    return self::$jsRootRelativeUrl.$namespace.'.js';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the full path to a web asset based on its relative URL.
   *
   * @param string $url The relative URL.
   *
   * @return string
   */
  protected function rootRelativeUrlToFullPath($url)
  {
    return self::$assetDir.$url;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
