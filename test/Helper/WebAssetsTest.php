<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Test\Helper;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\WebAssets;

//----------------------------------------------------------------------------------------------------------------------

/**
 * Test cases for class WebAssets.
 */
class WebAssetsTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public static function setUpBeforeClass()
  {
    WebAssets::$assetDir           = __DIR__;
    WebAssets::$cssRootRelativeUrl = '/WebAssetsTest/css/';
    WebAssets::$jsRootRelativeUrl  = '/WebAssetsTest/js/';
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendClassSpecificSource1()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendClassSpecificSource('SetBased\\Foo\\Bar');
    $webAssets->echoCascadingStyleSheets();

    $this->expectOutputString('<link href="/WebAssetsTest/css/SetBased/Foo/Bar.css" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendClassSpecificSource2()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendClassSpecificSource('SetBased\\Foo\\Bar', 'printer');
    $webAssets->echoCascadingStyleSheets();

    $this->expectOutputString('<link href="/WebAssetsTest/css/SetBased/Foo/Bar.printer.css" media="printer" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendSource1()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendSource('foo.css');
    $webAssets->echoCascadingStyleSheets();

    $this->expectOutputString('<link href="/WebAssetsTest/css/foo.css" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendSource2()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendSource('foo.css', 'printer');
    $webAssets->echoCascadingStyleSheets();

    $this->expectOutputString('<link href="/WebAssetsTest/css/foo.css" media="printer" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method echoPageTitle() with null.
   */
  public function testEchoPageTitle01()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle(null);
    $webAssets->echoPageTitle();

    $this->expectOutputString('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method echoPageTitle() with empty string.
   */
  public function testEchoPageTitle02()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('');
    $webAssets->echoPageTitle();

    self::assertSame('', $this->getActualOutput());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method echoPageTitle() with some string.
   */
  public function testEchoPageTitle03()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('Hello World');
    $webAssets->echoPageTitle();

    $this->expectOutputString('<title>Hello World</title>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmClassSpecificFunctionCall1()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmClassSpecificFunctionCall('SetBased\\Foo\\Bar', 'main');
    $webAssets->echoJavaScript();

    $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\/Bar\\"],function(page){\'use strict\';page.main();});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmClassSpecificFunctionCall2()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmClassSpecificFunctionCall('SetBased\\Foo\\Bar', 'main', ['foo', 1]);
    $webAssets->echoJavaScript();

    $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\/Bar\\"],function(page){\'use strict\';page.main(\\"foo\\",1);});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmFunctionCall1()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmFunctionCall('SetBased/Foo', 'main');
    $webAssets->echoJavaScript();

    $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\\"],function(page){\'use strict\';page.main();});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmFunctionCall2()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmFunctionCall('SetBased/Foo', 'main', ['foo', false]);
    $webAssets->echoJavaScript();

    $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\\"],function(page){\'use strict\';page.main(\\"foo\\",false);});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method setPageTitle() with null.
   */
  public function testSetPageTitle01()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle(null);

    self::assertSame('', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method setPageTitle() with empty string.
   */
  public function testSetPageTitle02()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('');

    self::assertSame('', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method setPageTitle() with non empty string.
   */
  public function testSetPageTitle03()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('Hello World');

    self::assertSame('Hello World', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method setPageTitle() with non empty string overriding previous set title.
   */
  public function testSetPageTitle04()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('Hello World');
    $webAssets->setPageTitle('Bye Bye');

    self::assertSame('Bye Bye', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method appendPageTitle() with null.
   */
  public function testAppendPageTitle01()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('Hello');
    $webAssets->appendPageTitle(null);

    self::assertSame('Hello', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method appendPageTitle() with empty string.
   */
  public function testAppendPageTitle02()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('Hello');
    $webAssets->appendPageTitle('');

    self::assertSame('Hello', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method appendPageTitle() with non empty string.
   */
  public function testAppendPageTitle03()
  {
    $webAssets = new WebAssets();

    $webAssets->setPageTitle('Hello');
    $webAssets->appendPageTitle('World');

    self::assertSame('Hello - World', $webAssets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
