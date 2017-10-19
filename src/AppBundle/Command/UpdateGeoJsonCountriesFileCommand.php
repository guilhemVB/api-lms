<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Currency;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\CurrencyRepository;
use AppBundle\Service\CSVParser;
use AppBundle\Twig\AssetExistsExtension;
use Doctrine\ORM\EntityManager;
use pcrov\JsonReader\JsonReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UpdateGeoJsonCountriesFileCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:update:geojson-country')
            ->setDescription("Permet de mettre à jour la liste des pays au format geojson");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->import($output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    private function import(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        /** @var Kernel $kernel */
        $kernel = $this->getContainer()->get('kernel');

        $countries = $countryRepository->findCountriesWithDestinations();

        $countryData = [];
        foreach ($countries as $country) {
            $countryData[] = [
                'id' => $country->getId(),
                'name' => $country->getName(),
                'slug' => $country->getSlug(),
                'priceAccommodation' => $country->getPriceAccommodation(),
                'priceLifeCost' => $country->getPriceLifeCost(),
                'totalPrices' => $country->getTotalPrices(),
                'codeAlpha2' => $country->getCodeAlpha2(),
                'codeAlpha3' => $country->getCodeAlpha3(),
                'viewUrl' =>  $kernel->getContainer()->get('router')->generate(
                    'country', ['slug' => $country->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL)
            ];
        }

        $fileName = $kernel->getProjectDir() . "/web/assets/geo-countries/data/countries.geojson";

        if (!file_exists($fileName) || !is_readable($fileName)) {
            $output->writeln("<error>Le fichier '$fileName' n'a pas été trouvé</error>");
            return [];
        }

        $reader = new JsonReader();
        $reader->open($fileName);

        $reader->read("features");

        ini_set('memory_limit', '256M');
        $reader->read();

        $newGeoJson = [];
        $newGeoJson["type"] = "FeatureCollection";
        $newGeoJson["features"] = [];

        $nbFound = 0;
        $nbToFind = count($countryData);
        do {
            $geoJsonCountry = $reader->value();
            $ISO_A3 = $geoJsonCountry['properties']["ISO_A3"];
            $keyCountryFound = array_search($ISO_A3, array_column($countryData, 'codeAlpha3'));

            if ($keyCountryFound !== false) {
                $geoJsonCountry["properties"] = array_merge($geoJsonCountry["properties"], $countryData[$keyCountryFound]);
                $newGeoJson["features"][] = $geoJsonCountry;

                $countryName = $countryData[$keyCountryFound]['name'];
                $nbFound++;
                $output->writeln("<info>--- $countryName trouvé - $nbFound / $nbToFind ---</info>");

                unset($countryData[$keyCountryFound]);
                $countryData = array_values($countryData);
                if ($nbFound == $nbToFind) {
                    break;
                }
            }

            $reader->next();
        } while ($geoJsonCountry);

        if ($nbFound != $nbToFind) {
            $output->writeln("<error>--- Pays trouvés : $nbFound / $nbToFind. Il manque :---</error>");
            var_dump($countryData);
        }

        $fp = fopen($kernel->getProjectDir() . "/web/files/countries.geojson", 'w');
        fwrite($fp, json_encode($newGeoJson));
        fclose($fp);
    }
}
