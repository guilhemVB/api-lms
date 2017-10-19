<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Destination;

class CrowFliesCalculator
{

    /**
     * @param Destination $destination1
     * @param Destination $destination2
     * @return float
     */
    public static function calculate(Destination $destination1, Destination $destination2)
    {
        return self::launchCalculation($destination1->getLatitude(), $destination1->getLongitude(), $destination2->getLatitude(), $destination2->getLongitude());
    }

    /**
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    private static function launchCalculation($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1.609344;
    }
}
