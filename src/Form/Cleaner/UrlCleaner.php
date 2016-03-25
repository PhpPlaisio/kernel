<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

use SetBased\Abc\Helper\Url;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Cleaner for normalizing URLs.
 */
class UrlCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var UrlCleaner
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return UrlCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a normalized URL if the submitted value is a URL. Otherwise returns the submitted value.
   *
   * @param string $theValue The submitted URL.
   *
   * @return string|null
   */
  public function clean($theValue)
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($theValue);

    // If the value is empty return immediately,
    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    // Split the URL in parts.
    $parts = parse_url($value);
    if (!is_array($parts))
    {
      return $value;
    }

    return Url::unParseUrl($parts, 'http');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
