<?php

namespace Voyager\Util;

use DateTime;
use DateTimeZone;
use Voyager\Util\Str;

class Chronos
{
    /**
     * Default active timezone.
     * 
     * @var string
     */

    private $timezone;

    /**
     * Store the DateTime object.
     * 
     * @var \Datetime
     */

    private $datetime;

    /**
     * Store errors that occurred.
     * 
     * @var array
     */

    private $errors;

    /**
     * Create new chronos Date and Time API object.
     * 
     * @return  void
     */
    
    public function __construct()
    {
        $this->timezone = app()->timezone;
        $this->datetime = new DateTime();
        $this->setTimezone($this->timezone);
        $this->setTimestamp('now');
        $this->errors = DateTime::getLastErrors();
    }

    /**
     * Generate and return DateTimeZone object.
     * 
     * @return  \DateTimeZone
     */

    public function getDateTimeZoneObject()
    {
        return new DateTimeZone($this->timezone);
    }

    /**
     * Return true if date and time is valid.
     * 
     * @return  true
     */

    public function isValid()
    {
        return $this->errors['warning_count'] === 0 && $this->errors['error_count'] === 0;
    }

    /**
     * Update current instance's timestamp.
     * 
     * @param   string $time
     * @return  $this
     */

    public function setTimestamp(string $time)
    {
        $this->datetime->setTimestamp(strtotime($time));
        
        return $this;
    }

    /**
     * Return current instance's timestamp.
     * 
     * @return  int
     */

    public function getTimestamp()
    {
        return $this->datetime->getTimestamp();
    }

    /**
     * Update the current timezone used.
     * 
     * @param   string $timezone
     * @return  $this
     */

    public function setTimezone(string $timezone)
    {
        $this->timezone = $timezone;
        $this->datetime->setTimezone($this->getDateTimeZoneObject());

        return $this;
    }

    /**
     * Return current instance's timezone.
     * 
     * @return  string
     */

    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Return current instance's year.
     * 
     * @return  int
     */

    public function getYear()
    {
        return (int) $this->format('Y');
    }

    /**
     * Return current instance's month.
     * 
     * @return  string
     */

    public function getMonth()
    {
        return $this->format('F');
    }

    /**
     * Return current instance's month.
     * 
     * @return  string
     */

    public function getMonthShort()
    {
        return $this->format('M');
    }

    /**
     * Return numeric representation of months.
     * 
     * @return  int
     */

    public function getMonthNumeric()
    {
        return (int) $this->format('n');
    }

    /**
     * Return current instance's day of the week.
     * 
     * @return  string
     */

    public function getDayOfWeek()
    {
        return $this->format('l');
    }

    /**
     * Return current instance's day of the week.
     * 
     * @return  string
     */

    public function getDayOfWeekShort()
    {
        return $this->format('D');
    }

    /**
     * Return current instance's day of the month.
     * 
     * @return  int
     */

    public function getDayOfMonth()
    {
        return (int) $this->format('j');
    }

    /**
     * Return current instance's day of the year.
     * 
     * @return  int
     */

    public function getDayOfYear()
    {
        return (int) $this->format('z');
    }

    /**
     * Return current instance's week of the year.
     * 
     * @return  int
     */

    public function getWeekOfYear()
    {
        return (int) $this->format('W');
    }

    /**
     * Return number of days in a month.
     * 
     * @return  int
     */

    public function getNumberOfDaysInMonth()
    {
        return (int) $this->format('t');
    }

    /**
     * Return true if year is leap year.
     * 
     * @return  bool
     */

    public function isLeapYear()
    {
        return (int) $this->format('L') === 1;
    }

    /**
     * Return the meridiem.
     * 
     * @return  string
     */

    public function getMeridiem()
    {
        return $this->format('a');
    }

    /**
     * Return 24 hour format.
     * 
     * @return  int
     */

    public function getHour()
    {
        return (int) $this->format('G');
    }

    /**
     * Return minutes.
     * 
     * @return  int
     */

    public function getMinutes()
    {
        return (int) $this->format('i');
    }

    /**
     * Return seconds.
     * 
     * @return  int
     */

    public function getSeconds()
    {
        return (int) $this->format('s');
    }

    /**
     * Return a RFC2822 date and time string.
     * 
     * @return  string
     */

    public function toRFC2822String()
    {
        return $this->format('r');
    }

    /**
     * Return a ISO8601 date and time string.
     * 
     * @return  string
     */

    public function toISO8601String()
    {
        return $this->format('c');
    }

    /**
     * Default date and time string format.
     * 
     * @return  string
     */

    public function toString()
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * Return date and time age.
     * 
     * @return  int
     */

    public function getAge()
    {
        return (int) $this->datetime->diff(new DateTime('now', $this->getDateTimeZoneObject()))->y;
    }

    /**
     * Return true if date and time has already passed.
     * 
     * @return  bool
     */

    public function hasPassed()
    {
        return new DateTime('now', $this->getDateTimeZoneObject()) > $this->datetime;
    }

    /**
     * Return true if date and time is in the future.
     * 
     * @return  bool
     */

    public function isFuture()
    {
        return new DateTime('now', $this->getDateTimeZoneObject()) < $this->datetime;
    }

    /**
     * Return true if date is today.
     * 
     * @return  bool
     */

    public function isToday()
    {
        $today = new DateTime('now', $this->getDateTimeZoneObject());
        $days = (int) round(($this->datetime->getTimestamp() - $today->getTimestamp()) / 86400);

        return $days === 0;
    }

    /**
     * Return true if date is yesterday.
     * 
     * @return  bool
     */

    public function isYesterday()
    {
        $yesterday = new DateTime('now', $this->getDateTimeZoneObject());
        $days = (int) round(($this->datetime->getTimestamp() - $yesterday->getTimestamp()) / 86400);

        return $days === -1;
    }

    /**
     * Return true if date is tomorrow.
     * 
     * @return  bool
     */

    public function isTomorrow()
    {
        $tomorrow = new DateTime('now', $this->getDateTimeZoneObject());
        $days = (int) round(($this->datetime->getTimestamp() - $tomorrow->getTimestamp()) / 86400);

        return $days === 1;
    }

    /**
     * Format timestamp in to readable date and time.
     * 
     * @param   string $format
     * @return  string
     */

    public function format(string $format)
    {
        return (string) $this->datetime->format($format);
    }

    /**
     * Parse date and time from string.
     * 
     * @param   string $time
     * @param   string $timezone
     * @return  $this
     */

    public static function parse(string $time, ?string $timezone = null)
    {
        $instance = new self();

        if(!is_null($timezone))
        {
            $instance->setTimezone($timezone);
        }

        return $instance->setTimestamp($time);
    }

    /**
     * Create new instance from date or with time.
     * 
     * @param   int $year
     * @param   int $month
     * @param   int $day
     * @param   int $hour
     * @param   int $minute
     * @param   int $year
     * @param   string $timezone
     * @return  $this
     */

    public static function create(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0, ?string $timezone = null)
    {
        $date = new Str();
        $date->append($year . '-')
             ->append(($month < 10 ? '0' . $month : $month) . '-')
             ->append(($day < 10 ? '0' . $day : $day))
             ->append(' ')
             ->append(($hour < 10 ? '0' . $hour : $hour) . ':')
             ->append(($minute < 10 ? '0' . $minute : $minute) . ':')
             ->append(($second < 10 ? '0' . $second : $second));

        return static::parse($date->get(), $timezone);
    }

    /**
     * Create instance from date.
     * 
     * @param   int $year
     * @param   int $month
     * @param   int $day
     * @param   string $timezone
     * @return  $this
     */

    public static function createFromDate(int $year, int $month, int $day, string $timezone = null)
    {
        return static::create($year, $month, $day, 0, 0, 0, $timezone);
    }

    /**
     * Return date and time instance now.
     * 
     * @param   string $timezone
     * @return  $this
     */

    public static function now(string $timezone = null)
    {
        return static::parse('now', $timezone);
    }

    /**
     * Return date and time instance for today.
     * 
     * @param   string $timezone
     * @return  $this
     */

    public static function today(string $timezone = null)
    {
        return static::parse('today', $timezone);
    }

    /**
     * Return date and time instance for yesterday.
     * 
     * @param   string $timezone
     * @return  $this
     */

    public static function yesterday(string $timezone = null)
    {
        return static::parse('yesterday', $timezone);
    }

    /**
     * Return date and time instance for tomorrow.
     * 
     * @param   string $timezone
     * @return  $this
     */

    public static function tomorrow(string $timezone = null)
    {
        return static::parse('tomorrow', $timezone);
    }

}