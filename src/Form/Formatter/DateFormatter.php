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
  private $format;

  /**
   * If set the date that will treated as an open date. An open date will be shown as an empty form control.
   *
   * @var string
   */
  private $openDate;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $format The date format, see <http://www.php.net/manual/function.date.php>.
   */
  public function __construct($format)
  {
    $this->format = $format;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If the machine value is a valid date returns the date formatted according the format specifier. Otherwise,
   * returns the machine value unchanged.
   *
   * @param string $value The machine value.
   *
   * @return string
   */
  public function format($value)
  {
    $match = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts);
    $valid = ($match && checkdate($parts[2], $parts[3], $parts[1]));
    if ($valid)
    {
      if ($value==$this->openDate) return '';

      $date = new \DateTime($value);

      return $date->format($this->format);
    }
    else
    {
      return $value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An open date will be shown as an empty field.
   *
   * @param string $openDate The open date in YYYY-MM-DD format.
   */
  public function setOpenDate($openDate)
  {
    $this->openDate = $openDate;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
