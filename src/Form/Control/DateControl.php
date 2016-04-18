<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\DateCleaner;
use SetBased\Abc\Form\Formatter\DateFormatter;
use SetBased\Abc\Form\Validator\DateValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls with jQuery UI datepicker.
 */
class DateControl extends TextControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   *
   * @var DateCleaner
   */
  protected $myCleaner;

  /**
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var DateFormatter
   */
  protected $myFormatter;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    $this->setAttrSize(10);
    $this->setAttrMaxLength(10);
    $this->addClass('datepicker');
    $this->setCleaner(new DateCleaner('d-m-Y', '-', '/. :\\'));
    $this->setFormatter(new DateFormatter('d-m-Y'));

    $this->addValidator(new DateValidator());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An empty submitted value will be replaced with the open date and an open date will be shown as
   * an empty field.
   *
   * @param string $theOpenDate The open date in YYYY-MM-DD format.
   */
  public function setOpenDate($theOpenDate)
  {
    $this->myCleaner->setOpenDate($theOpenDate);
    $this->myFormatter->setOpenDate($theOpenDate);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
