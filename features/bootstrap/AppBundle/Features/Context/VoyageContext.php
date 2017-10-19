<?php

namespace AppBundle\Features\Context;

use AppBundle\Repository\UserRepository;
use AppBundle\Service\CRUD\CRUDVoyage;
use AppBundle\Service\Stats\VoyageStats;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VoyageContext extends CommonContext
{
    /** @var CRUDVoyage */
    private $CRUDVoyage;

    /** @var VoyageStats */
    private $voyageStats;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->CRUDVoyage = $container->get('crud_voyage');
        $this->voyageStats = $container->get('voyage_stats');
    }

    /**
     * @When l'utilisateur :userName crée les voyages suivants :
     */
    public function lUtilisateurCreeLesVoyagesSuivants($userName, TableNode $tableVoyages)
    {
        $users = $this->findUsers($userName);
        $user = $users[0];

        foreach ($tableVoyages as $voyageRow) {
            $destination = $this->findDestinationByName($voyageRow['destination de départ']);
            $this->CRUDVoyage->add($user, $voyageRow['nom'], $voyageRow['date de départ'], $destination);
        }
    }

    /**
     * @Then les statistiques du voyage :voyageName sont :
     */
    public function lesStatistiquesDuVoyageSont($voyageName, TableNode $tableStats)
    {
        $voyage = $this->findVoyageByName($voyageName);
        $stats = $this->voyageStats->calculateAllStats($voyage, $voyage->getStages());

        foreach ($tableStats as $statsRow) {
            $this->assertEquals($statsRow['nb étapes'], $stats['nbStages']);
            $this->assertEquals($statsRow['cout total'], $stats['totalCost']);
            $this->assertEquals($statsRow['durée'], $stats['nbDays']);
            $this->assertEquals($statsRow['date départ'], $stats['startDate']->format('d/m/Y'));
            $this->assertEquals($statsRow['date retour'], $stats['endDate']->format('d/m/Y'));
            $this->assertEquals($statsRow['nb de pays'], $stats['nbCountries']);
            $this->assertEquals($statsRow['distance'], round($stats['crowFliesDistance']));
            $this->assertEquals($statsRow['étape principale'], $stats['mainDestination']['name']);
        }
    }
}
