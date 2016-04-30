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
  static private $singleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return TidyCleaner
   */
  public static function get()
  {
    if (!self::$singleton) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a HTML snippet cleaned by [HTML Tidy](http://www.html-tidy.org/).
   *
   * @param string $value The submitted HTML snippet.
   *
   * @return string|null
   */
  public function clean($value)
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($value);

    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    $tidy_config = ['clean'          => false,
                    'output-xhtml'   => true,
                    'show-body-only' => true,
                    'wrap'           => 100];

    $tidy = new \tidy;

    $tidy->parseString($value, $tidy_config, Html::$encoding);
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
