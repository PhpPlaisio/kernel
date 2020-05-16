<?php
declare(strict_types=1);

namespace Plaisio;

/**
 * Parent class for classes that are operating under PhpPlaisio.
 */
class PlaisioObject implements PlaisioInterface
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param PlaisioInterface $object The parent PhpPlaisio object.
   */
  public function __construct(PlaisioInterface $object)
  {
    $this->nub = $object->nub;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
