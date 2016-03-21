<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Cleaner for removing leading and training whitespace and replacing intermediate whitespace and multiple
 * intermediate whitespaces (including new lines and tabs) with a single space.
 */
class PruneWhitespaceCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var PruneWhitespaceCleaner
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return PruneWhitespaceCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with leading and training whitespace removed. Intermediate whitespace and multiple
   * intermediate whitespace (including new lines and tabs) are replaced with a single space.
   *
   * @param string $theValue The submitted value.
   *
   * @return string|null
   */
  public function clean($theValue)
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      return null;
    }

    $tmp = trim(mb_ereg_replace('[\ \t\n\r\0\x0B\xA0]+', ' ', $theValue, 'p'));
    if ($tmp==='') $tmp = null;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
