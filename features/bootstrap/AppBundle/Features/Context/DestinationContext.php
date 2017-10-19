<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Destination;
use AppBundle\Repository\DestinationRepository;
use AppKernel;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DestinationContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @When je modifie les destinations :
     */
    public function jeModifieLesDestinations(TableNode $tableDestinations)
    {
        foreach ($tableDestinations->getHash() as $destinationRow) {
            $destination = $this->findDestinationByName($destinationRow['nom']);
            $this->completeDestination($destination, $destinationRow);
        }
        $this->em->flush();
    }

    private function completeDestination(Destination $destination, $destinationRow)
    {
        $country = $this->findCountryByName($destinationRow['pays']);

        $destination->setName($destinationRow['nom'])
            ->setDescription(isset($destinationRow['description']) ? [$destinationRow['description']] : [])
            ->setTips(isset($destinationRow['bon plans']) ? $destinationRow['bon plans'] : '')
            ->setPriceAccommodation(isset($destinationRow["prix de l'hébergement"]) ? $destinationRow["prix de l'hébergement"] : 0)
            ->setPriceLifeCost(isset($destinationRow["prix du cout de la vie"]) ? $destinationRow["prix du cout de la vie"] : 0)
            ->setLongitude(isset($destinationRow['longitude']) ? $destinationRow['longitude'] : 2.336492)
            ->setLatitude(isset($destinationRow['latitude']) ? $destinationRow['latitude'] : 48.864592)
            ->setCountry($country)
            ->setIsPartial(isset($destinationRow['partielle']) ? $destinationRow['partielle'] === 'oui' : true);

        $this->em->persist($destination);

        $country->addDestination($destination);

        $this->em->persist($country);
    }
}
