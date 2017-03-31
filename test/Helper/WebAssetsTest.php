<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Test\Helper;

use SetBased\Abc\Helper\WebAssets;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Test cases for class WebAssets.
 */
class WebAssetsTest extends \PHPUnit_Framework_TestCase
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

    echo $this->expectOutputString('<link href="/WebAssetsTest/css/SetBased/Foo/Bar.css" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendClassSpecificSource2()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendClassSpecificSource('SetBased\\Foo\\Bar', 'printer');
    $webAssets->echoCascadingStyleSheets();

    echo $this->expectOutputString('<link href="/WebAssetsTest/css/SetBased/Foo/Bar.printer.css" media="printer" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendSource1()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendSource('foo.css');
    $webAssets->echoCascadingStyleSheets();

    echo $this->expectOutputString('<link href="/WebAssetsTest/css/foo.css" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testCssAppendSource2()
  {
    $webAssets = new WebAssets();

    $webAssets->cssAppendSource('foo.css', 'printer');
    $webAssets->echoCascadingStyleSheets();

    echo $this->expectOutputString('<link href="/WebAssetsTest/css/foo.css" media="printer" rel="stylesheet" type="text/css"/>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmClassSpecificFunctionCall1()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmClassSpecificFunctionCall('SetBased\\Foo\\Bar', 'main');
    $webAssets->echoJavaScript();

    echo $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\/Bar\\"],function(page){\'use strict\';page.main();});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmClassSpecificFunctionCall2()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmClassSpecificFunctionCall('SetBased\\Foo\\Bar', 'main', ['foo', 1]);
    $webAssets->echoJavaScript();

    echo $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\/Bar\\"],function(page){\'use strict\';page.main(\\"foo\\",1);});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmFunctionCall1()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmFunctionCall('SetBased/Foo', 'main');
    $webAssets->echoJavaScript();

    echo $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\\"],function(page){\'use strict\';page.main();});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testJsAdmFunctionCall2()
  {
    $webAssets = new WebAssets();

    $webAssets->jsAdmFunctionCall('SetBased/Foo', 'main', ['foo', false]);
    $webAssets->echoJavaScript();

    echo $this->expectOutputString('<script type="text/javascript">/*<![CDATA[*/set_based_abc_inline_js="require([],function(){require([\\"SetBased\/Foo\\"],function(page){\'use strict\';page.main(\\"foo\\",false);});});"/*]]>*/</script>');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
