<?php

namespace AppBundle\Entity;

use AppBundle\Service\CRUD\CRUDStage;
use Symfony\Component\Serializer\Annotation\Groups;

trait JourneyTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"journey"})
     * @Assert\Choice({"TRAIN", "BUS", "FLY", "NONE", null})
     */
    private $transportType;

    /**
     * @var AvailableJourney
     * @ORM\ManyToOne(targetEntity="AvailableJourney")
     * @Groups({"journey"})
     */
    private $availableJourney;

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
        if (is_null($transportType) || CRUDStage::BUS === $transportType || CRUDStage::TRAIN === $transportType || CRUDStage::FLY === $transportType || CRUDStage::NONE === $transportType) {
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
            case CRUDStage::BUS :
                $price = $availableJourney->getBusPrices();
                $time = $availableJourney->getBusTime();
                break;
            case CRUDStage::TRAIN :
                $price = $availableJourney->getTrainPrices();
                $time = $availableJourney->getTrainTime();
                break;
            case CRUDStage::FLY :
                $price = $availableJourney->getFlyPrices();
                $time = $availableJourney->getFlyTime();
                break;
            case CRUDStage::NONE :
                $price = 0;
                $time = 0;
                break;
            default :
                throw new \Exception("Unknow transportType '$transportType''");
        }

        return ['price' => $price, 'time' => $time, 'transportType' => $transportType];
    }

}
