<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Cleaner for removing all whitespace.
 */
class RemoveWhitespaceCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var RemoveWhitespaceCleaner
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return RemoveWhitespaceCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with all whitespace removed.
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

    $tmp = trim(mb_ereg_replace('[\ \t\n\r\0\x0B\xA0]+', '', $theValue, 'p'));
    if ($tmp==='') $tmp = null;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
