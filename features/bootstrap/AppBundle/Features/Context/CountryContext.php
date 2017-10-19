<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Country;
use AppKernel;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountryContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @Given les destinations par défaut :
     */
    public function lesDestinationsParDefaut(TableNode $tableCountries)
    {
        foreach ($tableCountries as $countryRow) {
            $country = $this->findCountryByName($countryRow['pays']);
            $destination = $this->findDestinationByName($countryRow['destination']);

            $country->setDefaultDestination($destination);

            $this->em->persist($country);
        }
        $this->em->flush();
    }


}
