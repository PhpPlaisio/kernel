<?php
declare(strict_types=1);

namespace Plaisio\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\PlaisioKernel;

/**
 * Test cases for PhpPlaisio's kernel.
 */
class KernelTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The test kernel.
   *
   * @var PlaisioKernel
   */
  private $kernel;

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
  public function testInvalidProperty(): void
  {
    $this->expectException(\LogicException::class);
    $this->expectExceptionMessage(sprintf('Unknown property %s::divideByZeo', PlaisioKernel::class));
    $this->kernel->divideByZeo;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getting a valid property.
   */
  public function testValidProperty(): void
  {
    // First time the object must be created.
    $dl1 = $this->kernel->DL;
    self::assertInstanceOf(\stdClass::class, $dl1);

    // Second time must return the same object.
    $dl2 = $this->kernel->DL;
    self::assertSame($dl1, $dl2);

    // Getter must be called only once.
    self::assertSame(1, $this->kernel->dlCount);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
