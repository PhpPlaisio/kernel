<?php
declare(strict_types=1);

namespace Plaisio\Test;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PlaisioObject.
 */
class ObjectTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The test kernel.
   *
   * @var TestKernel
   */
  private TestKernel $kernel;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setUp(): void
  {
    $this->kernel = new TestKernel();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getting a non-existing property.
   */
  public function testNub(): void
  {
    $object = new TestObject($this->kernel);

    self::assertSame($this->kernel, $object->nub);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
