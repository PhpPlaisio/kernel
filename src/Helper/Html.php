<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Helper;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Static class with helper functions for generating HTML code.
 */
class Html
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The encoding of the generated HTML code.
   *
   * @var string
   */
  public static $ourEncoding = 'UTF-8';

  /**
   * Counter for generating unique element IDs.
   *
   * @var int
   */
  private static $ourAutoId = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string proper conversion of special characters to HTML entities of an attribute of a HTML tag.
   *
   * Boolean attributes (e.g. checked, disabled and draggable, autocomplete also) are set when the value is none empty.
   *
   * @param string $theName  The name of the attribute.
   * @param mixed  $theValue The value of the attribute.
   *
   * @return string
   */
  public static function generateAttribute($theName, $theValue)
  {
    $html = '';

    switch ($theName)
    {
      // Boolean attributes.
      case 'autofocus':
      case 'checked':
      case 'disabled':
      case 'hidden':
      case 'ismap':
      case 'multiple':
      case 'novalidate':
      case 'readonly':
      case 'required':
      case 'selected':
      case 'spellcheck':
        if (!empty($theValue))
        {
          $html = ' ';
          $html .= $theName;
          $html .= '="';
          $html .= $theName;
          $html .= '"';
        }
        break;

      // Annoying boolean attribute exceptions.
      case 'draggable':
      case 'contenteditable':
        if (isset($theValue))
        {
          $html = ' ';
          $html .= $theName;
          $html .= ($theValue) ? '="true"' : '="false"';
        }
        break;

      case 'autocomplete':
        if (isset($theValue))
        {
          $html = ' ';
          $html .= $theName;
          $html .= ($theValue) ? '="on"' : '="off"';
        }
        break;

      case 'translate':
        if (isset($theValue))
        {
          $html = ' ';
          $html .= $theName;
          $html .= ($theValue) ? '="yes"' : '="no"';
        }
        break;

      default:
        if ($theValue!==null && $theValue!==false && $theValue!=='')
        {
          $html = ' ';
          $html .= htmlspecialchars($theName, ENT_QUOTES, self::$ourEncoding);
          $html .= '="';
          $html .= htmlspecialchars($theValue, ENT_QUOTES, self::$ourEncoding);
          $html .= '"';
        }
        break;
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates HTML code for an element.
   *
   * Note: tags for void elements such as '<br/>' are not supported.
   *
   * @param string $theTagName    The name of the tag, e.g. a, form.
   * @param array  $theAttributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                              HTML entities.
   * @param string $theInnerText  The inner text of the tag.
   * @param bool   $theIsHtmlFag  If set the inner text is a HTML snippet, otherwise special characters in the inner
   *                              text will be replaced with HTML entities.
   *
   * @return string
   */
  public static function generateElement($theTagName, $theAttributes = [], $theInnerText = '', $theIsHtmlFag = false)
  {
    $html = self::generateTag($theTagName, $theAttributes);
    $html .= ($theIsHtmlFag) ? $theInnerText : self::txt2Html($theInnerText);
    $html .= '</';
    $html .= $theTagName;
    $html .= '>';

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates HTML code for a start tag of an element.
   *
   * @param string $theTagName    The name of the tag, e.g. a, form.
   * @param array  $theAttributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                              HTML entities.
   *
   * @return string
   */
  public static function generateTag($theTagName, $theAttributes = [])
  {
    $html = '<';
    $html .= $theTagName;
    foreach ($theAttributes as $name => $value)
    {
      // Ignore attributes with leading underscore.
      if (strpos($name, '_')!==0) $html .= self::generateAttribute($name, $value);
    }
    $html .= '>';

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates HTML code for a void element.
   *
   * Void elements are: area, base, br, col, embed, hr, img, input, keygen, link, menuitem, meta, param, source, track,
   * wbr. See <http://www.w3.org/html/wg/drafts/html/master/syntax.html#void-elements>
   *
   * @param string $theTagName    The name of the tag, e.g. img, link.
   * @param array  $theAttributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                              HTML entities.
   *
   * @return string
   */
  public static function generateVoidElement($theTagName, $theAttributes = [])
  {
    $html = '<';
    $html .= $theTagName;
    foreach ($theAttributes as $name => $value)
    {
      // Ignore attributes with leading underscore.
      if (strpos($name, '_')!==0) $html .= self::generateAttribute($name, $value);
    }
    $html .= '/>';

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string that can be safely used as an ID for an element. The format of the id is 'abc_<n>' where n is
   * incremented with each call of this method.
   *
   * @return string
   */
  public static function getAutoId()
  {
    self::$ourAutoId++;

    return 'abc_'.self::$ourAutoId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with special characters converted to HTML entities.
   * This method is a wrapper around [htmlspecialchars](http://php.net/manual/en/function.htmlspecialchars.php).
   *
   * @param string $theString The string with optionally special characters.
   *
   * @return string
   */
  public static function txt2Html($theString)
  {
    return htmlspecialchars($theString, ENT_QUOTES, self::$ourEncoding);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
