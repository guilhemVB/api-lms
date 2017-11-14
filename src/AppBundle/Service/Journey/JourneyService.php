<?php

namespace AppBundle\Service\Journey;

use AppBundle\Entity\AvailableJourney;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\AvailableJourneyRepository;
use AppBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;

class JourneyService
{

    /**@var EntityManager */
    private $em;

    /** @var StageRepository */
    private $stageRepository;

    /** @var BestJourneyFinder */
    private $bestJourneyFinder;

    /** @var AvailableJourneyRepository */
    private $availableJourneyRepository;

    public function __construct(EntityManager $em, BestJourneyFinder $bestJourneyFinder)
    {
        $this->em = $em;
        $this->bestJourneyFinder = $bestJourneyFinder;

        $this->stageRepository = $em->getRepository('AppBundle:Stage');
        $this->availableJourneyRepository = $em->getRepository('AppBundle:AvailableJourney');
    }

    /**
     * @param Stage $stage
     * @param Stage|null $stageAfter
     * @return Stage
     * @throws \Exception
     */
    public function updateJourneyByStage(Stage $stage, Stage $stageAfter = null)
    {
        $voyage = $stage->getVoyage();
        if (is_null($stageAfter)) {
            $stageAfter = $this->stageRepository->findStageByPosition($voyage, $stage->getPosition() + 1);
        }

        if (is_null($stageAfter)) {
            $stage->setAvailableJourney(null);
            $stage->setTransportType(null);
        } else {
            /** @var AvailableJourney $availableJourney */
            $availableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $stage->getDestination(), 'toDestination' => $stageAfter->getDestination()]);

            if (is_null($availableJourney)) {
                $stage->setAvailableJourney(null);
                $stage->setTransportType(null);
            } else {
                $transportType = $this->bestJourneyFinder->chooseBestTransportType($availableJourney);

                $stage->setAvailableJourney($availableJourney);
                $stage->setTransportType($transportType);
            }
        }

        $this->em->persist($stage);
        $this->em->flush();

        return $stage;
    }

    /**
     * @param Voyage $voyage
     * @param Stage|null $stageAfter
     * @return Voyage
     * @throws \Exception
     */
    public function updateJourneyByVoyage(Voyage $voyage, Stage $stageAfter = null)
    {
        if (is_null($stageAfter)) {
            $stageAfter = $this->stageRepository->findStageByPosition($voyage, 0);
        }

        if (is_null($stageAfter)) {
            $voyage->setAvailableJourney(null);
            $voyage->setTransportType(null);
        } else {
            /** @var AvailableJourney $availableJourney */
            $availableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $voyage->getStartDestination(), 'toDestination' => $stageAfter->getDestination()]);

            if (is_null($availableJourney)) {
                $voyage->setAvailableJourney(null);
                $voyage->setTransportType(null);
            } else {
                $transportType = $this->bestJourneyFinder->chooseBestTransportType($availableJourney);

                $voyage->setAvailableJourney($availableJourney);
                $voyage->setTransportType($transportType);
            }
        }

        $this->em->persist($voyage);
        $this->em->flush();

        return $voyage;
    }

}
