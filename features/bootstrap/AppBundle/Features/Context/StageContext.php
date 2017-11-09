<?php

namespace AppBundle\Features\Context;

use AppBundle\Repository\StageRepository;
use AppBundle\Service\CRUD\StageManager;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StageContext extends CommonContext
{
    /** @var StageManager */
    private $stageManager;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->stageManager = $container->get('crud_stage');
    }

    /**
     * @When j'ajoute les étapes suivantes au voyage :voyageName :
     */
    public function jAjouteLesÉtapesSuivantesAuVoyage($voyageName, TableNode $tableStages)
    {
        $voyage = $this->findVoyageByName($voyageName);
        foreach ($tableStages as $stageRow) {
            if (!empty($stageRow['destination'])) {
                $destination = $this->findDestinationByName($stageRow['destination']);
                $this->stageManager->addDestination($destination, $voyage, $stageRow['nombre de jour']);
            } else {
                $country = $this->findCountryByName($stageRow['pays']);
                $this->stageManager->addCountry($country, $voyage, $stageRow['nombre de jour']);
            }
        }
    }

    /**
     * @When je supprime l'étape :destinationName à la position :position du voyage :voyageName
     */
    public function jeSupprimeLÉtapeALaPositionDuVoyage($destinationName, $position, $voyageName)
    {
        $destination = $this->findDestinationByName($destinationName);
        $voyage = $this->findVoyageByName($voyageName);
        $stages = $this->findStageByDestinationAndVoyage($destination, $voyage);

        foreach ($stages as $stage) {
            if ($stage->getPosition() == $position) {
                $this->stageManager->remove($stage);
                return;
            }
        }

        $this->fail("Stage '$destinationName' position $position not found");
    }

    /**
     * @Then la voyage :voyageName à les étapes suivantes :
     */
    public function laVoyageÀLesÉtapesSuivantes($voyageName, TableNode $tableStages)
    {
        $voyage = $this->findVoyageByName($voyageName);
        $stages = $voyage->getStages();

        $this->assertSameSize($tableStages, $stages);

        /** @var StageRepository $stageRepository */
        $stageRepository = $this->em->getRepository('AppBundle:Stage');

        foreach ($tableStages as $stageRow) {
            if (!empty($stageRow['destination'])) {
                $destination = $this->findDestinationByName($stageRow['destination']);
                $this->assertNotNull(
                    $stageRepository->findOneBy(['voyage' => $voyage, 'destination' => $destination, 'position' => $stageRow['position']]),
                    "Can't find destination " . $destination->getName());
            } else {
                $country = $this->findCountryByName($stageRow['pays']);
                $this->assertNotNull(
                    $stageRepository->findOneBy(['voyage' => $voyage, 'country' => $country, 'position' => $stageRow['position']]),
                    "Can't find country " . $country->getName());
            }
        }
    }

    /**
     * @When je change l'étape :destinationName du voyage :voyageName de la position :oldPosition à la position :newPosition
     */
    public function jeChangeLÉtapeDuVoyageDeLaPositionÀLaPosition($destinationName, $voyageName, $oldPosition, $newPosition)
    {
        $destination = $this->findDestinationByName($destinationName);
        $voyage = $this->findVoyageByName($voyageName);
        $stages = $this->findStageByDestinationAndVoyage($destination, $voyage);

        foreach ($stages as $stage) {
            if ($stage->getPosition() == $oldPosition) {
                $this->stageManager->changePosition($stage, $oldPosition, $newPosition);
                return;
            }
        }

        $this->fail("Stage '$destinationName' position $oldPosition not found");
    }
}
