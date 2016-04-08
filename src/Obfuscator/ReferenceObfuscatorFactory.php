<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Obfuscator;

use SetBased\Affirm\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A factory for obfuscators using a reference implementation for obfuscating database ID.
 */
class ReferenceObfuscatorFactory implements ObfuscatorFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A lookup table from label to [length, key, bit mask].
   *
   * @var array[]
   */
  public static $ourLabels = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public static function decode($theCode, $theLabel)
  {
    if (!isset(self::$ourLabels[$theLabel]))
    {
      throw new LogicException("Unknown label '%s'.", $theLabel);
    }

    return ReferenceObfuscator::decrypt($theCode,
                                        self::$ourLabels[$theLabel][0],
                                        self::$ourLabels[$theLabel][1],
                                        self::$ourLabels[$theLabel][2]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public static function encode($theId, $theLabel)
  {
    if (!isset(self::$ourLabels[$theLabel]))
    {
      throw new LogicException("Unknown label '%s'.", $theLabel);
    }

    return ReferenceObfuscator::encrypt($theId,
                                        self::$ourLabels[$theLabel][0],
                                        self::$ourLabels[$theLabel][1],
                                        self::$ourLabels[$theLabel][2]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * @return ReferenceObfuscator
   */
  public static function getObfuscator($theLabel)
  {
    if (!isset(self::$ourLabels[$theLabel]))
    {
      throw new LogicException("Unknown label '%s'.", $theLabel);
    }

    return new ReferenceObfuscator(self::$ourLabels[$theLabel][0],
                                   self::$ourLabels[$theLabel][1],
                                   self::$ourLabels[$theLabel][2]);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
