<?php

namespace AppBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use AppBundle\Entity\AvailableJourney;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\AvailableJourneyRepository;
use AppBundle\Repository\StageRepository;
use AppBundle\Repository\VoyageRepository;
use Doctrine\ORM\EntityManager;

class CRUDAvailableJourney
{

    /**
     * @var EntityManager
     */
    private $em;

    /** @var AvailableJourneyRepository */
    private $availableJourneyRepository;

    /** @var StageRepository */
    private $stageRepository;

    /** @var VoyageRepository */
    private $voyageRepository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->availableJourneyRepository = $em->getRepository('AppBundle:AvailableJourney');
        $this->stageRepository = $em->getRepository('AppBundle:Stage');
        $this->voyageRepository = $em->getRepository('AppBundle:Voyage');
    }

    /**
     * @param Destination $destination
     * @return AvailableJourney[]
     */
    public function findAvailableJourneysByDestination(Destination $destination)
    {
        $availableJourneys = $this->availableJourneyRepository->findBy(['fromDestination' => $destination]);

        return array_merge($availableJourneys, $this->availableJourneyRepository->findBy(['toDestination' => $destination]));
    }

    /**
     * @param Destination $destination
     * @return int
     */
    public function removeAvailableJourneyByDestination(Destination $destination)
    {
        $availableJourneys = $this->findAvailableJourneysByDestination($destination);

        foreach ($availableJourneys as $availableJourney) {
            $this->remove($availableJourney);
        }

        return count($availableJourneys);
    }

    /**
     * @param AvailableJourney $availableJourney
     */
    public function remove(AvailableJourney $availableJourney)
    {
        /** @var Stage[] $stagesWithThisAvailableJourney */
        $stagesWithThisAvailableJourney = $this->stageRepository->findBy(['availableJourney' => $availableJourney]);

        foreach ($stagesWithThisAvailableJourney as $stageWithThisAvailableJourney) {
            $stageWithThisAvailableJourney->setAvailableJourney(null);
            $stageWithThisAvailableJourney->setTransportType(null);
            $this->em->persist($stageWithThisAvailableJourney);
        }
        $this->em->flush();

        /** @var Voyage[] $voyagesWithThisAvailableJourney */
        $voyagesWithThisAvailableJourney = $this->voyageRepository->findBy(['availableJourney' => $availableJourney]);
        foreach ($voyagesWithThisAvailableJourney as $voyageWithThisAvailableJourney) {
            $voyageWithThisAvailableJourney->setAvailableJourney(null);
            $voyageWithThisAvailableJourney->setTransportType(null);
            $this->em->persist($voyageWithThisAvailableJourney);
        }
        $this->em->flush();

        $this->em->remove($availableJourney);
        $this->em->flush();
    }

}
