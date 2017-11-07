<?php

namespace AppBundle\Entity;

interface JourneyInterface {

    /**
     * @return AvailableJourney
     */
    public function getAvailableJourney();

    /**
     * @param AvailableJourney $availableJourney
     * @return $this
     */
    public function setAvailableJourney($availableJourney);

    /**
     * @return string
     */
    public function getTransportType();

    /**
     * @param string $transportType
     * @return $this
     * @throws \Exception
     */
    public function setTransportType($transportType);
}