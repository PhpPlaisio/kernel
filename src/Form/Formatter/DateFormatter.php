<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Formatter;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Formatter for formatting dates.
 */
class DateFormatter implements Formatter
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The format specifier, see <http://www.php.net/manual/function.date.php>.
   *
   * @var string
   */
  private $myFormat;

  /**
   * If set the date that will treated as an open date. An open date will be shown as an empty form control.
   *
   * @var string
   */
  private $myOpenDate;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theFormat The date format, see <http://www.php.net/manual/function.date.php>.
   */
  public function __construct($theFormat)
  {
    $this->myFormat = $theFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If the machine value is a valid date returns the date formatted according the format specifier. Otherwise,
   * returns the machine value unchanged.
   *
   * @param string $theValue The machine value.
   *
   * @return string
   */
  public function format($theValue)
  {
    $match = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $theValue, $parts);
    $valid = ($match && checkdate($parts[2], $parts[3], $parts[1]));
    if ($valid)
    {
      if ($theValue==$this->myOpenDate) return '';

      $date = new \DateTime($theValue);

      return $date->format($this->myFormat);
    }
    else
    {
      return $theValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An open date will be shown as an empty field.
   *
   * @param string $theOpenDate The open date in YYYY-MM-DD format.
   */
  public function setOpenDate($theOpenDate)
  {
    $this->myOpenDate = $theOpenDate;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
