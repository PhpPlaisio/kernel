<?php
declare(strict_types=1);

namespace Plaisio\Kernel\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\Kernel\Nub;

/**
 * Test cases for PhpPlaisio's kernel.
 */
class NubTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The test kernel.
   *
   * @var Nub
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
   * Test getting an non-existing property.
   */
  public function testInvalidProperty(): void
  {
    $this->expectException(\LogicException::class);
    $this->expectExceptionMessage('Unknown property Plaisio\Kernel\Nub::divideByZeo');
    Nub::$nub->divideByZeo;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getting a valid property.
   */
  public function testValidProperty(): void
  {
    // First time the object must be created.
    $dl1 = Nub::$nub->DL;
    self::assertInstanceOf(\stdClass::class, $dl1);

    // Second time must return the same object.
    $dl2 = Nub::$nub->DL;
    self::assertSame($dl1, $dl2);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
