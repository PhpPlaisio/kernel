<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * An Obfuscator for development environments only.
 *
 * This Obfuscator just prepends the label of a database ID to the database ID. This allows for easy inspecting database
 * IDs in URLs and HTML code and detecting programming errors where a database ID is obfuscated and de-obfuscated with
 * different labels.
 */
class DevelopmentObfuscator implements Obfuscator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The alias for the column with the database IDs.
   */
  private $myLabel;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theLabel An alias for the column with the database IDs.
   */
  public function __construct($theLabel)
  {
    $this->myLabel = $theLabel;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * Throws an exception if the database ID is obfuscated with different label.
   */
  public function decode($theCode)
  {
    return DevelopmentObfuscatorFactory::decode($theCode, $this->myLabel);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function encode($theId)
  {
    return DevelopmentObfuscatorFactory::encode($theId, $this->myLabel);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
