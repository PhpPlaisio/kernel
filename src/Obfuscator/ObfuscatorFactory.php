<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * An interface for defining factories for Obfuscator objects. Beside creating objects the interface specifies that the
 * factory must be able to obfuscate and de-obfuscate database ID by itself too.
 */
interface ObfuscatorFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * De-obfuscated database ID.
   *
   * @param string $theCode  The obfuscated database ID.
   * @param string $theLabel The alias for the column with the database IDs.
   *
   * @return int The (de-obfuscated) database ID.
   */
  public static function decode($theCode, $theLabel);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Obfuscates a database ID.
   *
   * @param int|null $theId    The database ID.
   * @param string   $theLabel The alias for the column with the database IDs.
   *
   * @return string The obfuscated database ID.
   */
  public static function encode($theId, $theLabel);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an Obfuscator for obfuscating and de-obfuscating database IDs.
   *
   * @param string $theLabel An alias for the column with the database IDs.
   *
   * @return Obfuscator
   */
  public static function getObfuscator($theLabel);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
