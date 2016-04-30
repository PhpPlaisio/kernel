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
  static private $singleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return UrlCleaner
   */
  public static function get()
  {
    if (!self::$singleton) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a normalized URL if the submitted value is a URL. Otherwise returns the submitted value.
   *
   * @param string $value The submitted URL.
   *
   * @return string|null
   */
  public function clean($value)
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($value);

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
