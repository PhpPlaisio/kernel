<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

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

    // Recompose the parts of the URL is a normalized URL.
    if (!isset($parts['scheme']) && !isset($parts['host']) && isset($parts['path']))
    {
      $i = strpos($parts['path'], '/');
      if ($i===false)
      {
        $parts['host'] = $parts['path'];
        unset($parts['path']);
      }
      else
      {
        $parts['host'] = substr($parts['path'], 0, $i);
        $parts['path'] = substr($parts['path'], $i);
      }
    }

    if (empty($parts['scheme']))
    {
      // The default scheme is 'http'.
      $parts['scheme'] = 'http';
    }
    else
    {
      // The schema must be in lowercase.
      $parts['scheme'] = strtolower($parts['scheme']);
    }

    // We assume that all URLs must have a path except for 'mailto'.
    if (!isset($parts['path']) && $parts['scheme']!='mailto')
    {
      $parts['path'] = '/';
    }

    // Recompose the URL starting with the scheme.
    if ($parts['scheme']=='mailto')
    {
      $url = 'mailto:';
    }
    else
    {
      $url = $parts['scheme'];
      $url .= '://';
    }

    if (isset($parts['pass']) && isset($parts['user']))
    {
      $url .= $parts['user'].':'.$parts['pass'].'@';
    }
    elseif (isset($parts['user']))
    {
      $url .= $parts['user'].'@';
    }

    if (isset($parts['host'])) $url .= $parts['host'];
    if (isset($parts['port'])) $url .= ':'.$parts['port'];
    if (isset($parts['path'])) $url .= $parts['path'];
    if (isset($parts['query'])) $url .= '?'.$parts['query'];
    if (isset($parts['fragment'])) $url .= '#'.$parts['fragment'];

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
