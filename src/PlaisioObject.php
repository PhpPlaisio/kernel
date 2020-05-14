<?php
declare(strict_types=1);

namespace Plaisio;

/**
 * Parent class for all classes that are operating under PhpPlaisio.
 */
class PlaisioObject
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The kernel of PhpPlaisio.
   *
   * @var PlaisioKernel
   */
  protected $nub;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param PlaisioObject $object The parent PhpPlaisio object.
   */
  public function __construct(PlaisioObject $object)
  {
    $this->nub = $object->nub;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
