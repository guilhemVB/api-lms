<?php

namespace AppBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use AppBundle\Entity\User;
use AppBundle\Entity\Voyage;
use AppBundle\Service\GoogleUrlShortener\GoogleUrlShortenerApiInterface;
use AppBundle\Service\Journey\JourneyService;
use Doctrine\ORM\EntityManager;

class VoyageManager
{

    /** @var JourneyService */
    private $journeyService;

    public function __construct(JourneyService $journeyService)
    {
        $this->journeyService = $journeyService;
    }


    /**
     * @param Voyage $voyage
     * @return Voyage
     */
    public function update(Voyage $voyage)
    {
        return $this->journeyService->updateJourneyByVoyage($voyage);
    }

}
