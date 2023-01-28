<?php
declare(strict_types=1);

namespace Plaisio\Test;

use Plaisio\PlaisioKernel;

/**
 * PlaisioKernel for testing purposes.
 */
class TestKernel extends PlaisioKernel
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The number of times method getDl(0 has been called.
   *
   * @var int
   */
  public int $dlCount = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getDl(): Object
  {
    $this->dlCount++;

    return new \stdClass();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
