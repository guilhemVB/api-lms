<?php

namespace AppBundle\Service\CRUD;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\AvailableJourneyRepository;
use AppBundle\Repository\StageRepository;
use AppBundle\Service\Journey\BestJourneyFinder;
use AppBundle\Service\Journey\JourneyService;
use Doctrine\ORM\EntityManager;

class StageManager
{

    const TRAIN = "TRAIN";
    const BUS = "BUS";
    const FLY = "FLY";
    const NONE = "NONE";

    /**
     * @var EntityManager
     */
    private $em;

    /** @var StageRepository */
    private $stageRepository;

    /** @var JourneyService */
    private $journeyService;

    public function __construct(EntityManager $em, JourneyService $journeyService)
    {
        $this->em = $em;
        $this->journeyService = $journeyService;
        $this->stageRepository = $em->getRepository('AppBundle:Stage');
    }

    /**
     * @param Voyage $voyage
     *
     * @return Voyage
     */
    public function updateAvailableJourneys(Voyage $voyage)
    {
        $stages = $voyage->getStages();
        $nbStage = $stages->count();

        if ($nbStage === 0) {
            return $voyage;
        }

        $this->journeyService->updateJourneyByVoyage($voyage, $stages[0]);

        for($i = 0 ; $i < $nbStage - 1 ; $i++) {
            $this->journeyService->updateJourneyByStage($stages[$i], $stages[$i+1]);
        }

        return $voyage;
    }
}
