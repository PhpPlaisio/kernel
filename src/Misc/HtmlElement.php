<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Misc;

use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for HTML elements.
 *
 * This class should be used for generation "heavy" HTML elements only. For light weight elements use methods of
 * {@link \SetBased\Abc\Helper\Html}.
 *
 * #### Global Attributes
 * This class defines methods for getting attributes and setting
 * [global attributes](http://www.w3schools.com/tags/ref_standardattributes.asp) only.
 *
 * Unless stated otherwise setting an attribute to null, false, or '' will unset the attribute.
 *
 * #### Event Attributes
 * This class does not defines methods for getting and setting event attributes. Events handles be setting at the load
 * event (with JavaScript).
 */
class HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The attributes of this HTML element.
   *
   * @var array
   */
  protected $myAttributes = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an attribute.
   *
   * @param string $theAttributeName The name of the attribute.
   *
   * @return mixed
   */
  public function getAttribute($theAttributeName)
  {
    return (isset($this->myAttributes[$theAttributeName])) ? $this->myAttributes[$theAttributeName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accesskey](http://www.w3schools.com/tags/att_global_accesskey.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrAccessKey($theValue)
  {
    $this->myAttributes['accesskey'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets or appends to the attribute [class](http://www.w3schools.com/tags/att_global_class.asp).
   *
   * If the class attribute is already set the value is appended (separated by a space) to the class attribute.
   *
   * Setting the value to null, false or '' will unset the class attribute.
   *
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrClass($theValue)
  {
    if ($theValue===null || $theValue===false || $theValue==='')
    {
      unset($this->myAttributes['class']);
    }
    else
    {
      if (isset($this->myAttributes['class']))
      {
        $this->myAttributes['class'] .= ' ';
        $this->myAttributes['class'] .= $theValue;
      }
      else
      {
        $this->myAttributes['class'] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [contenteditable](http://www.w3schools.com/tags/att_global_contenteditable.asp).
   * * Any value that evaluates to true will set the attribute to 'true'.
   * * Any value that evaluates to false will set the attribute to 'false'.
   * * Null will unset the attribute.
   *
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrContentEditable($theValue)
  {
    $this->myAttributes['contenteditable'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [contextmenu](http://www.w3schools.com/tags/att_global_contextmenu.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrContextMenu($theValue)
  {
    $this->myAttributes['contextmenu'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a [data](http://www.w3schools.com/tags/att_global_data.asp) attribute.
   *
   * @param string $theName  The name of the attribute (without 'data-').
   * @param string $theValue The attribute value.
   */
  public function setAttrData($theName, $theValue)
  {
    $this->myAttributes['data-'.$theName] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [dir](http://www.w3schools.com/tags/att_global_dir.asp). Possible values:
   * * ltr
   * * rtl
   * * auto
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrDir($theValue)
  {
    $this->myAttributes['dir'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [draggable](http://www.w3schools.com/tags/att_global_draggable.asp). Possible values:
   * * true
   * * false
   * * auto
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrDraggable($theValue)
  {
    $this->myAttributes['draggable'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [dropzone](http://www.w3schools.com/tags/att_global_dropzone.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrDropZone($theValue)
  {
    $this->myAttributes['dropzone'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [hidden](http://www.w3schools.com/tags/att_global_hidden.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'hidden'. Any other value will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrHidden($theValue)
  {
    $this->myAttributes['hidden'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [id](http://www.w3schools.com/tags/att_global_id.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrId($theValue)
  {
    $this->myAttributes['id'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [lang](http://www.w3schools.com/tags/att_global_lang.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrLang($theValue)
  {
    $this->myAttributes['lang'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [spellcheck](http://www.w3schools.com/tags/att_global_spellcheck.asp).
   * * Any value that evaluates to true will set the attribute to 'true'.
   * * Any value that evaluates to false will set the attribute to 'false'.
   * * Null will unset the attribute.
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrSpellCheck($theValue)
  {
    $this->myAttributes['spellcheck'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [style](http://www.w3schools.com/tags/att_global_style.asp)
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrStyle($theValue)
  {
    $this->myAttributes['style'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [tabindex](http://www.w3schools.com/tags/att_global_tabindex.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrTabIndex($theValue)
  {
    $this->myAttributes['tabindex'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [title](http://www.w3schools.com/tags/att_global_title.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrTitle($theValue)
  {
    $this->myAttributes['title'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [translate](http://www.w3schools.com/tags/att_global_translate.asp).
   * * Any value that evaluates to true will set the attribute to 'yes'.
   * * Any value that evaluates to false will set the attribute to 'no'.
   * * Null will unset the attribute.
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrTranslate($theValue)
  {
    $this->myAttributes['translate'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a fake attribute. A fake attribute has a name that starts with an underscore. Fake attributes will not be
   * included in the generated HTML code.
   *
   * @param string $theName  The name of the fake attribute.
   * @param mixed  $theValue The value of the fake attribute.
   */
  public function setFakeAttribute($theName, $theValue)
  {
    if (strpos($theName, '_')!==0)
    {
      throw new LogicException("Attribute '%s' is not a valid fake attribute.", $theName);
    }

    $this->myAttributes[$theName] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
