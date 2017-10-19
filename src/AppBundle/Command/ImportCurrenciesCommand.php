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

class ImportCurrenciesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:import:currencies')
            ->setDescription("Permet d'importer et mettre à jour la liste des devises")
            ->addArgument('fileName', InputArgument::REQUIRED, 'Nom du fichier csv à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->import($input, $output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function import(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CurrencyRepository $currencyRepository */
        $currencyRepository = $em->getRepository('AppBundle:Currency');

        $fileName = $input->getArgument('fileName');
        $dataCurrencies = CSVParser::extract($fileName, $input, $output);
        $nbCurrencies = count($dataCurrencies);
        $output->writeln("<info>--- $nbCurrencies devises ont été trouvés dans le fichier ---</info>");

        $nbToFlush = 0;
        foreach ($dataCurrencies as $dataCurrency) {
            $name = $dataCurrency['nom'];
            $code = $dataCurrency['code'];

            if (empty($name) || empty($code)) {
                $output->writeln("<error>ERREUR devise '$name' - $code</error>");
                continue;
            }

            $currency = $currencyRepository->findOneByCode($code);

            if (is_null($currency)) {
                $currency = new Currency();
                $output->writeln("<info>Nouvelle devise '$name' - $code</info>");
            } else {
                $output->writeln("<info>Modification de la devise '$name' - $code</info>");
            }
            $currency->setName($name)
                ->setCode($code);

            $em->persist($currency);
            $nbToFlush++;


            if ($nbToFlush % 50 == 0) {
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }
}
