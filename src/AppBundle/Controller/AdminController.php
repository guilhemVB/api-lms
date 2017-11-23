<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    public function commandImportCountriesAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:import:countries',
            'fileName' => '../web/files/pays.csv',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }

    public function commandImportCurrenciesAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:import:currencies',
            'fileName' => '../web/files/devises.csv',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }

    public function commandImportDestinationsAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:import:destinations',
            'fileName' => '../web/files/destinations.csv',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $input = new ArrayInput([
            'command' => 'app:footer',
        ]);
        $application->run($input, $output);

        $input = new ArrayInput([
            'command' => 'app:update:geojson-country',
        ]);
        $application->run($input, $output);

        $input = new ArrayInput([
            'command' => 'app:update:defaultDestination',
        ]);
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }

    public function commandUpdateRatesAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'app:update:rates']);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }
    
    public function commandCalculateJourneyAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'app:journey']);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }
}
