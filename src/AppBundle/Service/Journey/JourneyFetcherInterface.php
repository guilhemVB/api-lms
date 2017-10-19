<?php

namespace AppBundle\Service\Journey;

use AppBundle\Entity\Destination;

interface JourneyFetcherInterface
{
    /**
     * @param Destination $fromDestination
     * @param Destination $toDestination
     * @retunn array
     */
    public function fetch(Destination $fromDestination, Destination $toDestination);
}