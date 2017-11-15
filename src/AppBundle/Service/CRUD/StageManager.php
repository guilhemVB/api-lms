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
     * @param Stage $stage
     * @return Stage
     */
    public function checkAvailableJourneyAfterNewStage(Stage $stage)
    {
        $voyage = $stage->getVoyage();
        if ($stage->getPosition() === 0) {
            $this->journeyService->updateJourneyByVoyage($voyage, $stage);
        } else {
            $stageBefore = $this->stageRepository->findStageByPosition($voyage, $stage->getPosition() - 1);
            $this->journeyService->updateJourneyByStage($stageBefore, $stage);
        }

        return $stage;
    }

    /**
     * @param Stage $stage
     *
     * @return Stage
     */
    public function updateStage(Stage $stage)
    {
        $position = $stage->getPosition();
        $voyage = $stage->getVoyage();
        dump($position);
        if ($position === 0) {
            $this->journeyService->updateJourneyByVoyage($voyage, $stage);
        } else {
            $stageBefore = $this->stageRepository->findStageByPosition($voyage, $position-1);
            $this->journeyService->updateJourneyByStage($stageBefore, $stage);
        }

        $stageAfter = $this->stageRepository->findStageByPosition($voyage, $position+1);
        if (!is_null($stageAfter)) {
            $this->journeyService->updateJourneyByStage($stage, $stageAfter);
        }

        return $stage;
    }

    /**
     * @param Stage $stage
     */
    public function afterRemovedStage(Stage $stage)
    {
        $voyage = $stage->getVoyage();
        $position = $stage->getPosition();

        if ($position === 0) {
            $this->journeyService->updateJourneyByVoyage($voyage);
        } else {
            $stageBefore = $this->stageRepository->findStageByPosition($voyage, $position-1);
            $this->journeyService->updateJourneyByStage($stageBefore);
        }
    }


    /**
     * @param Stage $stage
     * @param int $oldPosition
     * @param int $newPosition
     * @return Stage
     */
    public function changePosition(Stage $stage, $oldPosition, $newPosition)
    {
        $voyage = $stage->getVoyage();
        if ($newPosition < $oldPosition) {
            $itPosition = $newPosition;
            while ($itPosition != $oldPosition) {
                /** @var Stage $stageIt */
                $stageIt = $this->stageRepository->findOneBy(['voyage' => $voyage, 'position' => $itPosition]);
                $itPosition++;
                $stageIt->setPosition($itPosition);
                $this->em->persist($stageIt);
            }
        } elseif ($newPosition > $oldPosition) {
            $itPosition = $newPosition;
            while ($itPosition != $oldPosition) {
                /** @var Stage $stageIt */
                $stageIt = $this->stageRepository->findOneBy(['voyage' => $voyage, 'position' => $itPosition]);
                $itPosition--;
                $stageIt->setPosition($itPosition);
                $this->em->persist($stageIt);
            }
        }

        $stage->setPosition($newPosition);
        $this->em->persist($stage);
        $this->em->flush();

        $this->journeyService->updateJourneyByStage($stage);

        if ($newPosition < $oldPosition) {
            $this->updateJourney($voyage, $oldPosition);
        } elseif ($newPosition > $oldPosition) {
            $this->updateJourney($voyage, $oldPosition - 1);
        }
        $this->updateJourney($voyage, $newPosition - 1);

        return $stage;
    }

    /**
     * @param Stage $stage
     * @param float $nbDays
     */
    public function changeNumberDays(Stage $stage, $nbDays)
    {
        $stage->setNbDays($nbDays);
        $this->em->persist($stage);
        $this->em->flush();
    }

    /**
     * @param Stage $stage
     * @param string $transportType
     */
    public function changeTransportType(Stage $stage, $transportType)
    {
        $stage->setTransportType($transportType);
        $this->em->persist($stage);
        $this->em->flush();
    }

    /**
     * @param Voyage $voyage
     * @param $position
     */
    private function updateJourney(Voyage $voyage, $position)
    {
        $stageBefore = $this->stageRepository->findStageByPosition($voyage, $position);
        if (is_null($stageBefore)) {
            $this->journeyService->updateJourneyByVoyage($voyage);
        } else {
            $this->journeyService->updateJourneyByStage($stageBefore);
        }
    }
}
