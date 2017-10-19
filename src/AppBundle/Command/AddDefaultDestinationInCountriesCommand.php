<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Currency;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\CurrencyRepository;
use AppBundle\Service\CSVParser;
use AppBundle\Twig\AssetExistsExtension;
use Doctrine\ORM\EntityManager;
//use pcrov\JsonReader\JsonReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddDefaultDestinationInCountriesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:update:defaultDestination')
            ->setDescription("Permet de choisir la destination par défaut dans chaque pays");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->launch($output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    private function launch(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $countries = $countryRepository->findCountriesWithDestinations();

        $cptFlush = 10;
        foreach ($countries as $country) {
            $destinations = $country->getDestinations();
            $capitalName = $country->getCapitalName();

            foreach ($destinations as $destination) {
                if ($destination->getName() === $capitalName) {
                    $country->setDefaultDestination($destination);
                    break;
                }
            }

            if (is_null($country->getDefaultDestination())) {
                $country->setDefaultDestination($destinations[0]);
            }

            if ($cptFlush%30 === 0) {
                $em->flush();
            }
            $cptFlush++;
        }

        $em->flush();
    }
}
