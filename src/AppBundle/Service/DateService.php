<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 15:57
 */

namespace AppBundle\Service;


class DateService
{

    protected $mfs;
    /**
     * DateService constructor.
     */
    public function __construct(MessagesFlashService $messagesFlashService)
    {
        $this->mfs = $messagesFlashService;
    }


    /**
     * Return true if the day is a public holiday in Franche
     * false otherwise
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isPublicHolidayInFrance(\DateTime $dateTime){
        $ts = $dateTime->getTimestamp();
        foreach($this->getHolidays(intval($dateTime->format("Y"))) as $timestamp){
            if($ts === $timestamp){
                return true;
            }
        }

        return false;
    }

    /**
     * @param \DateTime $date
     * @return int Age
     */
    public function calculateAge(\DateTime $date){
        $year = intval($date->format("Y"));
        $month = intval($date->format("m"));
        $day = intval($date->format("d"));

        $now = new \DateTime();
        $year2d = intval($now->format("Y"));
        $month2d = intval($now->format("m"));
        $day2d = intval($now->format("d"));

        $age = $year2d - $year;

        if(($month === $month2d && $day2d < $day)||($month2d < $month)){
            $age --;
        }

        return $age;

    }

    /**
     * Return an array of public holiday in france for the $year
     * or actual year if null
     *
     * @param null $year
     * @return array
     */
    private function getHolidays($year = null)
    {
        if ($year === null)
        {
            $year = intval(date('Y'));
        }

        $easterDate  = mktime(0, 0, 0, 3,  21,  $year) + ( 24 *3600 * easter_days($year));
        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $holidays = array(
            // Dates fixes
            mktime(0, 0, 0, 1,  1,  $year),  // 1st of the year
            mktime(0, 0, 0, 5,  1,  $year),  // Labor Day
            mktime(0, 0, 0, 5,  8,  $year),  // 1945 Victory
            mktime(0, 0, 0, 7,  14, $year),  // French National Day
            mktime(0, 0, 0, 8,  15, $year),  // Assumption
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // 1st WW armistice
            mktime(0, 0, 0, 12, 25, $year),  // Christmas Day
            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
        );

        sort($holidays);

        return $holidays;
    }

    /**
     * Return true if $dateTime is today
     * false otherwise
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isToday(\DateTime $dateTime){
        $today = date("Ymd");
        $dateToVerif = $dateTime->format("Ymd");
        if(intval($today) === intval($dateToVerif)){
            return true;
        }
        return false;
    }

}

