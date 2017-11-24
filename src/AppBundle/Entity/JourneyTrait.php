<?php

namespace AppBundle\Entity;

use AppBundle\Service\CRUD\StageManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait JourneyTrait
{

    /**
     * @return AvailableJourney
     */
    public function getAvailableJourney()
    {
        return $this->availableJourney;
    }

    /**
     * @param AvailableJourney $availableJourney
     * @return $this
     */
    public function setAvailableJourney($availableJourney)
    {
        $this->availableJourney = $availableJourney;

        return $this;
    }

    /**
     * @param string $transportType
     * @return $this
     * @throws \Exception
     */
    public function setTransportType($transportType)
    {
        if (is_null($transportType) || StageManager::BUS === $transportType || StageManager::TRAIN === $transportType || StageManager::FLY === $transportType || StageManager::NONE === $transportType) {
            $this->transportType = $transportType;
        } else {
            throw new \Exception("Unknow transportType '$transportType''");
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    public function getPriceAndTimeTransport()
    {
        $availableJourney = $this->getAvailableJourney();
        $transportType = $this->getTransportType();

        if (empty($transportType)) {
            return null;
        }

        $price = null;
        $time = null;

        switch ($transportType) {
            case StageManager::BUS :
                $price = $availableJourney->getBusPrices();
                $time = $availableJourney->getBusTime();
                break;
            case StageManager::TRAIN :
                $price = $availableJourney->getTrainPrices();
                $time = $availableJourney->getTrainTime();
                break;
            case StageManager::FLY :
                $price = $availableJourney->getFlyPrices();
                $time = $availableJourney->getFlyTime();
                break;
            case StageManager::NONE :
                $price = 0;
                $time = 0;
                break;
            default :
                throw new \Exception("Unknow transportType '$transportType''");
        }

        return ['price' => $price, 'time' => $time, 'transportType' => $transportType];
    }

}
