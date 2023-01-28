<?php
declare(strict_types=1);

namespace Plaisio;

use Plaisio\Kernel\Nub;

/**
 * The heart of the Plaisio system and parent class for all kernels.
 */
#[\AllowDynamicProperties]
abstract class PlaisioKernel implements PlaisioInterface
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @deprecated
   *
   * @var int
   */
  public int $pagIdIndex;

  /**
   * @deprecated
   *
   * @var array|null
   */
  public ?array $pageInfo = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * PlaisioKernel constructor.
   */
  public function __construct()
  {
    $this->nub = $this;
    Nub::$nub  = $this;
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
  /**
   * Returns the URL of the login page.
   *
   * @param string|null $redirect After a successful login the user agent must be redirected to this URL.
   *
   * @return string
   * @deprecated
   */
  public function getLoginUrl(?string $redirect = null): string
  {
    unset($redirect);

    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
