<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc;

//----------------------------------------------------------------------------------------------------------------------
class Babel
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the (default) language.
   *
   * @var int
   */
  protected static $lanId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the language.
   *
   * @return int
   */
  public static function getLanId()
  {
    if (!self::$lanId)
    {
      self::$lanId = Abc::getInstance()->getLanId();
    }

    return self::$lanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a text.
   *
   * @param int        $txtId The ID of the text.
   * @param array|null $args  The arguments when the text is a format string.
   *
   * @return string
   */
  public static function getText($txtId, $args = null)
  {
    if (!self::$lanId)
    {
      self::$lanId = Abc::getInstance()->getLanId();
    }

    $text = Abc::$DL->bblTextGetText($txtId, self::$lanId);

    if (empty($args))
    {
      return $text['ttt_text'];
    }

    return vsprintf($text['ttt_text'], $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the text of a word.
   *
   * @param int $wrdId The ID of the word.
   *
   * @return string
   */
  public static function getWord($wrdId)
  {
    if (!self::$lanId)
    {
      self::$lanId = Abc::getInstance()->getLanId();
    }

    return Abc::$DL->bblWordGetWord($wrdId, self::$lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the language.
   *
   * @param int $lanId The ID of the language.
   */
  public static function setLanId($lanId)
  {
    self::$lanId = $lanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
