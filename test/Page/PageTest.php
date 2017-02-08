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
   * Test cases for getCgiUrl.
   */
  public function testGetCgiUrl()
  {
    // Test for null.
    $_GET['foo'] = null;
    $value       = Page::getCgiUrl('foo');
    $this->assertEquals('', $value, 'null 1');

    unset($_GET['foo']);
    $value = Page::getCgiUrl('foo');
    $this->assertEquals('', $value, 'null 2');

    // Test for null with default.
    $_GET['foo'] = null;
    $value       = Page::getCgiUrl('foo', '/bar');
    $this->assertEquals('/bar', $value, 'null with default 1');

    unset($_GET['foo']);
    $value = Page::getCgiUrl('foo', '/bar');
    $this->assertEquals('/bar', $value, 'null with default 2');

    // Test for special characters.
    $_GET['foo'] = '%2F';
    $value       = Page::getCgiUrl('foo');
    $this->assertEquals('/', $value, 'special characters 1');

    $_GET['foo'] = 'https%3A%2F%2Fwww.setbased.nl%2F';
    $value       = Page::getCgiUrl('foo', null, false);
    $this->assertEquals('https://www.setbased.nl/', $value, 'special characters 2');

    // Test for special characters with default.
    $_GET['foo'] = '%2F';
    $value       = Page::getCgiUrl('foo', 'spam');
    $this->assertEquals('/', $value, 'special characters with default 1');

    $_GET['foo'] = 'https%3A%2F%2Fwww.setbased.nl%2F';
    $value       = Page::getCgiUrl('foo', 'spam', false);
    $this->assertEquals('https://www.setbased.nl/', $value, 'special characters with default 2');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases for getCgiVar.
   */
  public function testGetCgiVar()
  {
    // Test for null.
    $_GET['foo'] = null;
    $value       = Page::getCgiVar('foo');
    $this->assertEquals('', $value, 'null 1');

    unset($_GET['foo']);
    $value = Page::getCgiVar('foo');
    $this->assertEquals('', $value, 'null 2');

    // Test for null with default.
    $_GET['foo'] = null;
    $value       = Page::getCgiVar('foo', 'bar');
    $this->assertEquals('bar', $value, 'null with default 1');

    unset($_GET['foo']);
    $value = Page::getCgiVar('foo', 'bar');
    $this->assertEquals('bar', $value, 'null with default 2');

    // Test for empty string.
    $_GET['foo'] = '';
    $value       = Page::getCgiVar('foo');
    $this->assertEquals('', $value, 'empty');

    // Test for empty string with default.
    $_GET['foo'] = '';
    $value       = Page::getCgiVar('foo', 'bar');
    $this->assertEquals('', $value, 'empty with default');

    // Test for normal string.
    $_GET['foo'] = 'bar';
    $value       = Page::getCgiVar('foo');
    $this->assertEquals('bar', $value, 'normal');

    // Test for normal string with default.
    $_GET['foo'] = 'bar';
    $value       = Page::getCgiVar('foo', 'eggs');
    $this->assertEquals('bar', $value, 'normal with default');

    // Test for special characters.
    $_GET['foo'] = '%2F';
    $value       = Page::getCgiVar('foo');
    $this->assertEquals('/', $value, 'special characters 1');

    $_GET['foo'] = 'https%3A%2F%2Fwww.setbased.nl%2F';
    $value       = Page::getCgiVar('foo');
    $this->assertEquals('https://www.setbased.nl/', $value, 'special characters 2');

    // Test for special characters with default.
    $_GET['foo'] = '%2F';
    $value       = Page::getCgiVar('foo', 'spam');
    $this->assertEquals('/', $value, 'special characters with default 1');

    $_GET['foo'] = 'https%3A%2F%2Fwww.setbased.nl%2F';
    $value       = Page::getCgiVar('foo', 'spam');
    $this->assertEquals('https://www.setbased.nl/', $value, 'special characters with default 2');
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
  /**
   * Test cases for putCgiSlugName.
   */
  public function testPutCgiSlugName()
  {
    // Test for null.
    $part = Page::putCgiSlugName('Perché l\'erba è verde?');
    $this->assertEquals('/perche-l-erba-e-verde.html', $part);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases for putCgiUrl.
   */
  public function testPutCgiUrl()
  {
    // Test for null.
    $part = Page::putCgiUrl('foo', null);
    $this->assertEquals('', $part);

    // Test for empty string.
    $part = Page::putCgiUrl('foo', '');
    $this->assertEquals('/foo/', $part);

    // Test for normal string.
    $part = Page::putCgiUrl('foo', 'bar');
    $this->assertEquals('/foo/bar', $part);

    // Test for special characters.
    $part = Page::putCgiUrl('foo', '/');
    $this->assertEquals('/foo/%2F', $part);

    // Test for special characters.
    $part = Page::putCgiUrl('foo', 'https://www.setbased.nl/');
    $this->assertEquals('/foo/https%3A%2F%2Fwww.setbased.nl%2F', $part);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases for putCgiVar.
   */
  public function testPutCgiVar()
  {
    // Test for null.
    $part = Page::putCgiVar('foo', null);
    $this->assertEquals('', $part);

    // Test for empty string.
    $part = Page::putCgiVar('foo', '');
    $this->assertEquals('/foo/', $part);

    // Test for normal string.
    $part = Page::putCgiVar('foo', 'bar');
    $this->assertEquals('/foo/bar', $part);

    // Test for special characters.
    $part = Page::putCgiVar('foo', '/');
    $this->assertEquals('/foo/%2F', $part);

    // Test for special characters.
    $part = Page::putCgiVar('foo', 'https://www.setbased.nl/');
    $this->assertEquals('/foo/https%3A%2F%2Fwww.setbased.nl%2F', $part);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
