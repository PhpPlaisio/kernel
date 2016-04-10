<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Cleaner\DateCleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class DateCleanerTest
 */
class DateCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  function makeCleaner()
  {
    return new DateCleaner('d-m-Y');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against ISO 8601 format.
   */
  public function testClean1()
  {
    // Test ISO 8601.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '1966-04-10';
    $value   = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against Dutch format 'd-m-Y'.
   */
  public function testClean2()
  {
    $cleaner = new DateCleaner('d-m-Y');

    // Test against ISO 8601.
    $raw   = '1966-04-10';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value);

    // Test against format.
    $raw   = '10-04-1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against English format 'm/d/Y'.
   */
  public function testClean3()
  {
    $cleaner = new DateCleaner('m/d/Y');

    // Test against ISO 8601.
    $raw   = '1966-04-10';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value);

    // Test against format.
    $raw   = '04/10/1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value);

    // Test against format.
    $raw   = '4/10/1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against English format 'm/d/Y' with alternative separators.
   */
  public function testClean4()
  {
    $cleaner = new DateCleaner('m-d-Y', '-', '/-. ');

    // Test against ISO 8601.
    $raw   = '1966-04-10';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '04-10-1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '4-10-1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '04.10-1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '4 10 1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '4 10.1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('1966-04-10', $value, "Raw: $raw");
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against Dutch format 'd-m-Y' with illegal date, alternative separators and whitespace.
   */
  public function testClean5()
  {
    $cleaner = new DateCleaner('m/d/Y', '-', '/-. ');

    // Test against format.
    $raw   = '31/11.1966 ';
    $value = $cleaner->clean($raw);
    $this->assertEquals('31-11-1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against English format 'm/d/Y' with illegal date and alternative separators.
   */
  public function testClean6()
  {
    $cleaner = new DateCleaner('m/d/Y', '/', '/-. ');

    // Test against format.
    $raw   = '11/31/1966';
    $value = $cleaner->clean($raw);
    $this->assertEquals('11/31/1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against illegal dates.
   */
  public function testClean10()
  {
    // String with 3 parts.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '10-april-1966';
    $value   = $cleaner->clean($raw);
    $this->assertEquals('10-april-1966', $value);

    // Some other string.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = 'Hello world.';
    $value   = $cleaner->clean($raw);
    $this->assertEquals('Hello world.', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
