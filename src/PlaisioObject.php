<?php
declare(strict_types=1);

namespace Plaisio;

/**
 * Parent class for classes that are operating under Plaisio.
 */
#[\AllowDynamicProperties]
class PlaisioObject implements PlaisioInterface
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param PlaisioInterface $nub The parent Plaisio object.
   */
  public function __construct(PlaisioInterface $nub)
  {
    $this->nub = $nub->nub;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
