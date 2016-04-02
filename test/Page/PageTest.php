<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
class PageText extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Bivalent logic test cases for getCgiBool.
   */
  public function testGetCgiBool2()
  {
    // Tests for true.
    $_GET['foo'] = true;
    $value       = Page::getCgiBool('foo');
    $this->assertSame(true, $value, 'test with true');

    $_GET['foo'] = 'hello';
    $value       = Page::getCgiBool('foo');
    $this->assertSame(true, $value, 'test with hello');

    $_GET['foo'] = ['bar'];
    $value       = Page::getCgiBool('foo');
    $this->assertSame(true, $value, 'test with array');


    // Tests for false.
    $_GET['foo'] = false;
    $value       = Page::getCgiBool('foo');
    $this->assertSame(false, $value, 'test with true');

    $_GET['foo'] = '';
    $value       = Page::getCgiBool('foo');
    $this->assertSame(false, $value, 'test with empty string');

    $_GET['foo'] = null;
    $value       = Page::getCgiBool('foo');
    $this->assertSame(false, $value, 'test with null');

    unset($_GET['foo']);
    $value = Page::getCgiBool('foo');
    $this->assertSame(false, $value, 'test not set');

    $_GET['foo'] = [];
    $value       = Page::getCgiBool('foo');
    $this->assertSame(false, $value, 'test empty array');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Bivalent logic test cases for getCgiBool.
   */
  public function testGetCgiBool3()
  {
    // Tests for true.
    $_GET['foo'] = true;
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(true, $value, 'test with true');

    $_GET['foo'] = 'hello';
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(true, $value, 'test with hello');

    $_GET['foo'] = ['bar'];
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(true, $value, 'test with array');


    // Tests for false.
    $_GET['foo'] = false;
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(false, $value, 'test with true');

    $_GET['foo'] = '';
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(false, $value, 'test with empty string');

    $_GET['foo'] = [];
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(false, $value, 'test empty array');


    // Tests for null.
    $_GET['foo'] = null;
    $value       = Page::getCgiBool('foo', true);
    $this->assertSame(null, $value, 'test with null');

    unset($_GET['foo']);
    $value = Page::getCgiBool('foo', true);
    $this->assertSame(null, $value, 'test not set');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Bivalent logic test cases for putCgiBool.
   */
  public function testPutCgiBool2()
  {
    // Tests for true.
    $part = Page::putCgiBool('foo', true);
    $this->assertEquals('/foo/1', $part, 'test with true');

    $part = Page::putCgiBool('foo', 'hello');
    $this->assertEquals('/foo/1', $part, 'test with hello');

    $part = Page::putCgiBool('foo', ['bar']);
    $this->assertEquals('/foo/1', $part, 'test with array');


    // Tests for false.
    $part = Page::putCgiBool('foo', false);
    $this->assertSame('', $part);

    $part = Page::putCgiBool('foo', null);
    $this->assertSame('', $part);

    $part = Page::putCgiBool('foo', '');
    $this->assertSame('', $part);

    $part = Page::putCgiBool('foo', []);
    $this->assertSame('', $part);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Trinary logic test cases for putCgiBool.
   */
  public function testPutCgiBool3()
  {
    // Tests for true.
    $part = Page::putCgiBool('foo', true, true);
    $this->assertEquals('/foo/1', $part);

    $part = Page::putCgiBool('foo', 'hello', true);
    $this->assertEquals('/foo/1', $part);

    $part = Page::putCgiBool('foo', ['bar'], true);
    $this->assertEquals('/foo/1', $part);


    // Tests for false.
    $part = Page::putCgiBool('foo', false, true);
    $this->assertSame('/foo/0', $part);

    $part = Page::putCgiBool('foo', '', true);
    $this->assertSame('/foo/0', $part);

    $part = Page::putCgiBool('foo', [], true);
    $this->assertSame('/foo/0', $part);


    // Tests for null.
    $part = Page::putCgiBool('foo', null, true);
    $this->assertSame('', $part);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
