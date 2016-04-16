<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Helper;

use SetBased\Exception\RuntimeException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Static class with helper functions for hashing and verifying passwords.
 */
class Password
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The algorithmic cost that should be used in [password_hash](http://php.net/manual/function.password-hash.php).
   *
   * @var int
   */
  public static $ourCost = 14;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a hashed password using PHP native [password_hash](http://php.net/manual/function.password-hash.php)
   * function.
   *
   * @param string $thePassword The password (given by the user).
   *
   * @return string The hashed password.
   */
  public static function passwordHash($thePassword)
  {
    $options = ['cost' => self::$ourCost];

    $hash = password_hash($thePassword, PASSWORD_DEFAULT, $options);
    if ($hash===false)
    {
      throw new RuntimeException('Function password_hash failed');
    }

    return $hash;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Checks if the given hash matches the given options using PHP native
   * [password_needs_rehash](http://php.net/manual/function.password-needs-rehash.php) function.
   *
   * @param string $theHash The hash (stored in the system).
   *
   * @return bool True if and only if the password matches with the hash value.
   */
  public static function passwordNeedsRehash($theHash)
  {
    $options = ['cost' => self::$ourCost];

    return password_needs_rehash($theHash, PASSWORD_DEFAULT, $options);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Verifies that a password matches a hash using PHP native
   * [password_verify](http://php.net/manual/function.password-verify.php) function.
   *
   * @param string $thePassword The password (given by the user).
   * @param string $theHash     The hash (stored in the system).
   *
   * @return bool True if and only if the password matches with the hash value.
   */
  public static function passwordVerify($thePassword, $theHash)
  {
    return password_verify($thePassword, $theHash);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
