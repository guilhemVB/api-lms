<?php

namespace AppBundle\Service\Tools;

class DestinationPeriods
{

    /**
     * @return array
     */
    public static function getPeriods()
    {
        return [
            1  => 'january',
            2  => 'february',
            3  => 'march',
            4  => 'april',
            5  => 'may',
            6  => 'june',
            7  => 'july',
            8  => 'august',
            9  => 'september',
            10 => 'october',
            11 => 'november',
            12 => 'december',
        ];
    }

    /**
     * @param int $monthNumber
     * @return string
     */
    public static function getCorrelationFromMonthNumberToMonthName($monthNumber)
    {
        $data = self::getPeriods();
        if (!isset($data[$monthNumber])) {
            return $data[1];
        }
        return $data[$monthNumber];
    }
}
