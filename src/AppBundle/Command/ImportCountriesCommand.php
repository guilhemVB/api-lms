<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Currency;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\CurrencyRepository;
use AppBundle\Service\CSVParser;
use AppBundle\Twig\AssetExistsExtension;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCountriesCommand extends ContainerAwareCommand
{

    /** @var  AssetExistsExtension */
    private $assetExistsExtension;

    /** @var  string */
    private $imagePath;

    protected function configure()
    {
        $this
            ->setName('app:import:countries')
            ->setDescription("Permet d'importer et mettre à jour la liste des pays, utiliser l'option -f pour forcer l'insert")
            ->addArgument('fileName', InputArgument::REQUIRED, 'Nom du fichier csv à importer')
            ->addOption('force', '-f');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $forceInsert = $input->getOption('force');
        $this->import($input, $output, $forceInsert);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param boolean $forceInsert
     */
    private function import(InputInterface $input, OutputInterface $output, $forceInsert)
    {
        $this->assetExistsExtension = new AssetExistsExtension($this->getContainer()->get('kernel'));
        $this->imagePath = $this->getContainer()->getParameter('image_banner_countries_path');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $fileName = $input->getArgument('fileName');
        $dataCountries = CSVParser::extract($fileName, $input, $output);
        $nbCountries = count($dataCountries);
        $output->writeln("<info>--- $nbCountries pays ont été trouvés dans le fichier ---</info>");

        $nbToFlush = 0;
        foreach ($dataCountries as $dataCountry) {
            $name = $dataCountry['nom'];
            $languages = $dataCountry['langues'];

            $country = $countryRepository->findOneByName($name);

            $isNew = false;
            if (is_null($country)) {
                $country = new Country();
                $country->setName($name);
                $isNew = true;
            }

            $currency = $this->getCurrency($dataCountry['code devise']);

            $country->setRedirectToDestination($dataCountry['doit être redirigé vers la destination'] === 'oui')
                ->setCodeAlpha2($dataCountry['code alpha 2'])
                ->setCodeAlpha3($dataCountry['code alpha 3'])
                ->setCurrency($currency)
                ->setLanguages(!empty($languages) ? explode("\n", $languages) : [])
                ->setCapitalName($dataCountry['capitale'])
                ->setVisaDuration($dataCountry['Durée du visa'])
                ->setVisaInformation($dataCountry['visa']);

            $country = $this->fetchAutomaticDataFromApi($output, $country);

            if ($this->isComplete($output, $country) || $forceInsert) {
                $em->persist($country);
                $nbToFlush++;

                if ($isNew) {
                    $output->writeln("<info>Nouveau pays '$name'</info>");
                } else {
                    $output->writeln("<info>Modification de '$name'</info>");
                }
            }

            if ($nbToFlush % 50 == 0) {
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }

    /**
     * @param OutputInterface $output
     * @param Country $country
     * @return Country
     */
    private function fetchAutomaticDataFromApi(OutputInterface $output, Country $country)
    {
        $name = $country->getName();

        $lat = $country->getLatitude();
        $lon = $country->getLongitude();
        $population = $country->getPopulation();

        if (!empty($lat) && !empty($lon) && !empty($population)) {
            return $country;
        }

        $code = $country->getCodeAlpha3();

        if (empty($code)) {
            $output->writeln("<error>Pays '$name'  --  Code Alpha3 inconnu, impossible de récupérer les données de l'API.</error>");
            return $country;
        }

        $url = "http://restcountries.eu/rest/v1/alpha?codes=$code";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $countriesData = curl_exec($ch);
        curl_close($ch);

        $countriesData = json_decode($countriesData, true);

        if (empty($countriesData) || is_null($countriesData[0])) {
            $output->writeln("<error>Pays '$name'  --  Impossible de trouver le pays avec le code '$code'. URL : '$url'.</error>");
            return $country;
        }
        $countryData = $countriesData[0];

        $country->setLatitude($countryData['latlng'][0])
            ->setLongitude($countryData['latlng'][1])
            ->setPopulation($countryData['population']);

        $output->writeln("<comment>Pays '$name'  --  Utilisation de l'API pour récupérer des infos sur le pays.</comment>");

        return $country;
    }

    /**
     * @param OutputInterface $output
     * @param Country $country
     * @return bool
     */
    private function isComplete(OutputInterface $output, Country $country)
    {
        if ($country->isRedirectToDestination()) {
            return true;
        }

        $currency = $country->getCurrency();
        $capitalName = $country->getCapitalName();
        $codeAlpha2 = $country->getCodeAlpha2();
        $codeAlpha3 = $country->getCodeAlpha3();
        $languages = $country->getLanguages();
        $lat = $country->getLatitude();
        $lon = $country->getLongitude();
        $name = $country->getName();
        $population = $country->getPopulation();
        $visaInformation = $country->getVisaInformation();
        $visaDuration = $country->getVisaDuration();

        $errors = [];
        if (empty($currency)) {
            $errors[] = 'Devise inconnue';
        }
        if (empty($capitalName)) {
            $errors[] = 'Nom de la capitale inconnue';
        }
        if (empty($codeAlpha2)) {
            $errors[] = 'code Alpha 2 inconnu';
        }
        if (empty($codeAlpha3)) {
            $errors[] = 'code Alpha 3 inconnu';
        }
        if (empty($languages)) {
            $errors[] = 'langages inconnus';
        }
        if (empty($lat)) {
            $errors[] = 'latitude inconnue';
        }
        if (empty($lon)) {
            $errors[] = 'longitude inconnue';
        }
        if (empty($name)) {
            $errors[] = 'nom inconnue';
        }
        if (empty($population)) {
            $errors[] = 'population inconnue';
        }
        if (empty($visaInformation)) {
            $errors[] = 'informations sur les prix du visa inconnues';
        }
        if (empty($visaDuration)) {
            $errors[] = 'informations sur les durées du visa inconnue';
        }
        $country->generateSlug();
        if (!$this->assetExistsExtension->assetExist($this->imagePath . $country->getSlug() . '.JPG')) {
            $errors[] = "pas d'image";
        }

        $codeAlpha2 = strtolower($codeAlpha2);
        $url = "http://www.geonames.org/flags/x/$codeAlpha2.gif";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!(curl_exec($ch) !== FALSE)) {
            $errors[] = "drapeau impossible à récupérer";
        }

        if (!empty($errors)) {
            $output->writeln("<error>Pays '$name'  --  ERREURS : " . join(' ; ', $errors) . ".</error>");

            return false;
        }

        return true;
    }

    /**
     * @param string $currencyCode
     * @return Currency|null
     */
    private function getCurrency($currencyCode)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CurrencyRepository $currencyRepository */
        $currencyRepository = $em->getRepository('AppBundle:Currency');

        return $currencyRepository->findOneByCode($currencyCode);
    }
}
