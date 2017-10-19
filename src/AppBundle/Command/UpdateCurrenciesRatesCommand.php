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

class UpdateCurrenciesRatesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:update:rates')
            ->setDescription("Permet de mettre à jour les taux de changes");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->updateRates($input, $output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    private function updateRates(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CurrencyRepository $currencyRepository */
        $currencyRepository = $em->getRepository('AppBundle:Currency');

        /** @var Currency[] $currencies */
        $currencies = $currencyRepository->findAll();

        $lastRatesData = $this->fetchLastRates($output);

        var_dump($lastRatesData);

        if (is_null($lastRatesData)) {
            $output->writeln("<error>Impossible de récupérer les données depuis l'API.</error>");

            return false;
        }

        $rates = $lastRatesData['quotes'];

        $usdeur = $rates['USDEUR'];
        if (empty($usdeur)) {
            $output->writeln("<error>Le taux de l'Euro n'a pas été trouvée, impossible de continuer</error>");

            return false;
        }

        foreach ($currencies as $currency) {
            $code = $currency->getCode();
            $name = $currency->getName();

            if (isset($rates['USD' . $code])) {
                $currency->setUsdRate($rates['USD' . $code]);
            } else {
                $output->writeln("<error>La devise $code n'a pas été trouvée</error>");
                break;
            }

            $currency->setEurRate($currency->getUsdRate() / $usdeur);

            $output->writeln("<info>MAJ de $name - $code</info>");
            $em->persist($currency);
        }

        $em->flush();
        $em->clear();

        return true;
    }

    /**
     * @param OutputInterface $output
     * @return array|null
     */
    private function fetchLastRates(OutputInterface $output)
    {
        $key = $this->getContainer()->getParameter('api_rate_key');

        if (is_null($key)) {
            $output->writeln("<error>La clé API est inconnue. Voir parameter.yml</error>");

            return null;
        }

        $url = 'http://www.apilayer.net/api/live?access_key=' . $key;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $rateData = curl_exec($ch);
        curl_close($ch);

        $rateData = json_decode($rateData, true);

        return $rateData;
    }
}
