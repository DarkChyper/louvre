<?php


namespace AppBundle\Service;

use PHPUnit\Framework\TestCase;

class DateServiceTest extends TestCase
{

    /**
     * @dataProvider dateProvider
     */
    public function testIsPublicHolidayInFrance($date, $expected){

        $dateService = new DateService();

        $this->assertSame($expected, $dateService->isPublicHolidayInFrance($date));
    }

    public function dateProvider(){
        return [
            [\DateTime::createFromFormat('j-m-Y H:i:s', '25-12-2019 00:00:00'),true], // christmas
            [\DateTime::createFromFormat('j-m-Y H:i:s', '04-03-2018 00:00:00'),false], // normal day
            [\DateTime::createFromFormat('j-m-Y H:i:s', '02-04-2018 00:00:00'),true], // easter day
            [\DateTime::createFromFormat('j-m-Y H:i:s', '08-05-2020 00:00:00'),true], // victory day
            [\DateTime::createFromFormat('j-m-Y H:i:s', '11-11-2022 00:00:00'),true], // Toussaints day
            [\DateTime::createFromFormat('j-m-Y H:i:s', '10-06-2019 00:00:00'),true], // Monday of Pentecote
        ];
    }
}