<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Cleaner for cleaning HTML code using [HTML Tidy](http://www.html-tidy.org/).
 */
class TidyCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var TidyCleaner
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return TidyCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a HTML snippet cleaned by [HTML Tidy](http://www.html-tidy.org/).
   *
   * @param string $theValue The submitted HTML snippet.
   *
   * @return string|null
   */
  public function clean($theValue)
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($theValue);

    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    $tidy_config = ['clean'          => false,
                    'output-xhtml'   => true,
                    'show-body-only' => true,
                    'wrap'           => 100];

    $tidy = new \tidy;

    $tidy->parseString($value, $tidy_config, Html::$ourEncoding);
    $tidy->cleanRepair();
    $value = trim(tidy_get_output($tidy));

    // In some cases Tidy returns an empty paragraph only.
    if (preg_match('/^(([\ \r\n\t])|(<p>)|(<\/p>)|(&nbsp;))*$/', $value)==1)
    {
      $value = null;
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
