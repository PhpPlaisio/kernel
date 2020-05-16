<?php
declare(strict_types=1);

namespace Plaisio;

/**
 * The heart of the PhpPlaisio system and parent class for all kernels.
 */
abstract class PlaisioKernel implements PlaisioInterface
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * PlaisioKernel constructor.
   */
  public function __construct()
  {
    $this->nub = $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a property.
   *
   * Do not call this method directly as it is a PHP magic method that
   * will be implicitly called when executing `$value = $object->property;`.
   *
   * @param string $property The name of the property.
   *
   * @return mixed The value of the property.
   *
   * @throws \LogicException If the property is not defined.
   */
  public function __get(string $property)
  {
    $getter = 'get'.ucfirst($property);
    if (method_exists($this, $getter))
    {
      return $this->$property = $this->$getter();
    }

    throw new \LogicException(sprintf('Unknown property %s::%s', __CLASS__, $property));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
