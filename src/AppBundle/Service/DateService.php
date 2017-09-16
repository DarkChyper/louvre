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

    public function isAvailableVisitDay(\DateTime $dateTime,$ticketType){
        $retour = true;

        // is sunday or tueasday ?
        if($dateTime->format("N") === 2
            OR $dateTime->format("N") === 7 ){

            $this->mfs->messageError("Il n'est pas possible de réserver des billets les mardis et dimanches.");
            $retour = false;

        }

        if($this->isPublicHolidayInFrance($dateTime)){

            $this->mfs->messageError($dateTime->format("d/m/Y") ." est un jour férié en France. Le musée sera fermé ce jour là.");
            $retour = false;

            // message service
        }

        // is today after 2pm and full day tickets ?
        if($ticketType === "FULL" && $this->isToday($dateTime)){

        }

        return $retour;
    }

    /**
     * Return true if the day is a public holiday in Franche
     * false otherwise
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    private function isPublicHolidayInFrance(\DateTime $dateTime){
        $ts = $dateTime->getTimestamp();
        foreach($this->getHolidays(intval($dateTime->format("Y"))) as $timestamp){
            if($ts === $timestamp){
                return true;
            }
        }

        return false;
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
        $marchTS = mktime(0, 0, 0, 3,  21,  $year);

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
    private function isToday(\DateTime $dateTime){
        $today = date("Ymd");
        $dateToVerif = $dateTime->format("Ymd");
        if(intval($today) === intval($dateToVerif)){
            return true;
        }
        return false;
    }

}

