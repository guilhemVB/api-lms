<?php

namespace AppBundle\Worker;

use AppBundle\Entity\Destination;
use AppBundle\Entity\JourneyInterface;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\AvailableJourneyRepository;
use AppBundle\Repository\StageRepository;
use AppBundle\Repository\VoyageRepository;
use AppBundle\Service\Journey\BestJourneyFinder;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class UpdateVoyageWorker
{

    /** @var EntityManager */
    private $em;

    /** @var AvailableJourneyRepository */
    private $availableJourneyRepository;

    /** @var VoyageRepository */
    private $voyageRepository;

    /** @var StageRepository */
    private $stageRepository;

    /** @var BestJourneyFinder */
    private $bestJourneyFinder;

    public function __construct(EntityManager $em, BestJourneyFinder $bestJourneyFinder)
    {
        $this->em = $em;
        $this->bestJourneyFinder = $bestJourneyFinder;

        $this->availableJourneyRepository = $em->getRepository('AppBundle:AvailableJourney');
        $this->voyageRepository = $em->getRepository('AppBundle:Voyage');
        $this->stageRepository = $em->getRepository('AppBundle:Stage');
    }

    public function run()
    {
        /** @var Voyage[] $voyages */
        $voyages = $this->voyageRepository->findAll();

        foreach ($voyages as $voyage) {
            $this->updateAvailableJourneys($voyage);
        }
    }

    /**
     * @param Voyage $voyage
     */
    private function updateAvailableJourneys(Voyage $voyage)
    {
        /** @var Stage[] $stages */
        $stages = $this->stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        if (empty($stages)) {
            $this->resetVoyageOrStage($voyage);

            return;
        } else {
            $firstStage = $stages[0];
            $this->updateAvailableJourneyIfNeeded($voyage, $voyage->getStartDestination(), $firstStage->getDestination());
        }

        for ($i = 1; $i < count($stages); $i++) {
            $stageA = $stages[$i - 1];
            $stageB = $stages[$i];

            $this->updateAvailableJourneyIfNeeded($stageA, $stageA->getDestination(), $stageB->getDestination());
        }

        $this->em->flush();
    }

    /**
     * @param JourneyInterface $voyageOrStage
     * @param Destination $fromDestination
     * @param Destination $toDestination
     */
    private function updateAvailableJourneyIfNeeded(JourneyInterface $voyageOrStage, Destination $fromDestination, Destination $toDestination)
    {
        $availableJourney = $voyageOrStage->getAvailableJourney();

        if (!is_null($availableJourney) &&
            $availableJourney->getFromDestination()->getId() == $fromDestination->getId() &&
            $availableJourney->getToDestination()->getId() == $toDestination->getId() &&
            !is_null($voyageOrStage->getTransportType())
        ) {
            return;
        }

        $availableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $fromDestination, 'toDestination' => $toDestination]);

        if (is_null($availableJourney)) {
            $this->resetVoyageOrStage($voyageOrStage);
            return;
        }

        $voyageOrStage->setAvailableJourney($availableJourney);
        $voyageOrStage->setTransportType($this->bestJourneyFinder->chooseBestTransportType($availableJourney));

        $this->em->persist($voyageOrStage);
    }

    /**
     * @param JourneyInterface $voyageOrStage
     */
    private function resetVoyageOrStage(JourneyInterface $voyageOrStage)
    {
        $voyageOrStage->setAvailableJourney(null);
        $voyageOrStage->setTransportType(null);

        $this->em->persist($voyageOrStage);
    }
}
