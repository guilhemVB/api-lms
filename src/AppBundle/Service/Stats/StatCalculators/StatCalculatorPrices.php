<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class StatCalculatorPrices implements StatCalculatorInterface
{
    /** @var int */
    private $totalCostAccommodation = 0;

    /** @var int */
    private $totalCostLifeCost = 0;

    /** @var int */
    private $totalTransportCost = 0;

    public function addFirstStep(Voyage $voyage)
    {
        if ($voyage->getTransportType()) {
            $priceAndTime = $voyage->getPriceAndTimeTransport();
            $this->totalTransportCost += $priceAndTime['price'];
        }
    }

    public function addStage(Stage $stage)
    {
        $priceAccommodation = 0;
        $priceLifeCost = 0;
        if (!is_null($stage->getDestination())) {
            $priceAccommodation = $stage->getDestination()->getPriceAccommodation();
            $priceLifeCost = $stage->getDestination()->getPriceLifeCost();
        } elseif (!is_null($stage->getCountry())) {
            $priceAccommodation = $stage->getCountry()->getPriceAccommodation();
            $priceLifeCost = $stage->getCountry()->getPriceLifeCost();
        }
        $nbDays = $stage->getNbDays();
        if ($stage->getTransportType()) {
            $priceAndTime = $stage->getPriceAndTimeTransport();
            $this->totalTransportCost += $priceAndTime['price'];
        }
        $this->totalCostAccommodation += $priceAccommodation * $nbDays;
        $this->totalCostLifeCost += $priceLifeCost * $nbDays;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return [
            'totalCost'              => $this->totalCostAccommodation + $this->totalCostLifeCost + $this->totalTransportCost,
            'totalCostAccommodation' => $this->totalCostAccommodation,
            'totalCostLifeCost'      => $this->totalCostLifeCost,
            'totalCostTransport'     => $this->totalTransportCost,
        ];
    }
}
