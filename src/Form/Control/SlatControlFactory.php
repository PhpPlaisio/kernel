<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\SlatJoint\SlatJoint;
use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for factories for creating slat controls.
 */
abstract class SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set to true the header will contain a row for filtering.
   *
   * @var bool
   */
  protected $myFilter = false;

  /**
   * The slat joints for the louver control of this slat control factory.
   *
   * @var SlatJoint[]
   */
  protected $mySlatJoints;

  /**
   * The number of columns in the under lying table of the slat form control.
   *
   * @var int
   */
  private $myNumberOfColumns = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a slat joint (i.e. a column to the form) to this slat control factory and returns this slat joint.
   *
   * @param string    $theSlatJointName The name of the slat joint.
   * @param SlatJoint $theSlatJoint     The slat joint.
   *
   * @return SlatJoint
   */
  public function addSlatJoint($theSlatJointName, $theSlatJoint)
  {
    $this->mySlatJoints[$theSlatJointName] = $theSlatJoint;

    $this->myNumberOfColumns += $theSlatJoint->getColSpan();

    return $theSlatJoint;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form control using a slat joint and returns the created form control.
   *
   * @param ComplexControl $theParentControl The parent form control for the created form control.
   * @param string         $theSlatJointName The name of the slat joint.
   * @param string|null    $theControlName   The name of the created form control. If null the form control will have
   *                                         the same name as the slat joint. Use '' for an empty name (should only be
   *                                         used if the created form control is a complex form control).
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckboxesControl|RadiosControl
   */
  public function createFormControl($theParentControl, $theSlatJointName, $theControlName = null)
  {
    $control = $this->mySlatJoints[$theSlatJointName]->createControl(isset($theControlName) ?
                                                                       $theControlName : $theSlatJointName);
    $theParentControl->addFormControl($control);

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form controls of a slat in a louver control.
   *
   * @param LouverControl $theLouverControl The louver control.
   * @param array         $theData          An array from the nested arrays as set in LouverControl::setData.
   *
   * @return SlatControl
   */
  abstract public function createRow($theLouverControl, $theData);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disables filtering.
   */
  public function disableFilter()
  {
    $this->myFilter = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables filtering.
   */
  public function enableFilter()
  {
    $this->myFilter = true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the colgroup element of the table form control.
   *
   * @return string
   */
  public function getColumnGroup()
  {
    $ret = '';
    foreach ($this->mySlatJoints as $factory)
    {
      $ret .= $factory->getHtmlColumn();
    }

    $ret .= '<col/>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the thead element of the table form control.
   *
   * @return string
   */
  public function getHtmlHeader()
  {
    $ret = '<tr class="header">';
    foreach ($this->mySlatJoints as $factory)
    {
      $ret .= $factory->getHtmlColumnHeader();
    }
    $ret .= '<td class="error"></td>';
    $ret .= '</tr>';

    if ($this->myFilter)
    {
      $ret .= '<tr class="filter">';
      foreach ($this->mySlatJoints as $factory)
      {
        $ret .= $factory->getHtmlColumnFilter();
      }
      $ret .= '<td class="error"></td>';
      $ret .= '</tr>';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the number of columns in the underlying table of the louver form control.
   *
   * @return int
   */
  public function getNumberOfColumns()
  {
    return $this->myNumberOfColumns;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the 0-indexed ordinal of a slat joint in the underlying table of the louver form control.
   *
   * @param string $theSlatJointName The name of the slat joint.
   *
   * @return int
   * @throws LogicException
   */
  public function getOrdinal($theSlatJointName)
  {
    $ordinal = 0;
    $key     = null;
    foreach ($this->mySlatJoints as $key => $slat_joint)
    {
      if ($key==$theSlatJointName) break;

      $ordinal += $slat_joint->getColSpan();
    }

    if ($key!=$theSlatJointName)
    {
      throw new LogicException("SlatJoint '%s' not found.", $theSlatJointName);
    }

    return $ordinal;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
