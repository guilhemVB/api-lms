<?php

namespace AppBundle\Service\Journey;


use AppBundle\Entity\AvailableJourney;
use AppBundle\Service\CRUD\StageManager;

class BestJourneyFinder
{

    /**
     * @param AvailableJourney $availableJourney
     * @return string
     */
    public function chooseBestTransportType(AvailableJourney $availableJourney)
    {
        $transports = [];

        $busPrice = $availableJourney->getBusPrices();
        if (!is_null($busPrice)) {
            $transports[StageManager::BUS] = $busPrice;
        }

        $trainPrice = $availableJourney->getTrainPrices();
        if (!is_null($trainPrice)) {
            $transports[StageManager::TRAIN] = $trainPrice;
        }

        $flyPrice = $availableJourney->getFlyPrices();
        if (!is_null($flyPrice)) {
            $transports[StageManager::FLY] = $flyPrice;
        }

        if (empty($transports)) {
            return StageManager::NONE;
        }

        $minTransportKey = (array_keys($transports, min($transports)));

        return $minTransportKey[0];
    }
}
