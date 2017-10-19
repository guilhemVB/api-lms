<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use AppKernel;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class FeatureContext implements Context, SnippetAcceptingContext
{

    /** @BeforeScenario */
    public function before($event)
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $application = new Application($kernel);
        $application->setAutoExit(false);
        FeatureContext::runConsole($application, 'doctrine:schema:drop', ['--force' => true, '--full-database' => true]);
        FeatureContext::runConsole($application, 'doctrine:schema:create');
        $kernel->shutdown();
    }

    /**
     * @param Application $application
     * @param string $command
     * @param array $options
     * @return int
     */
    public static function runConsole($application, $command, $options = [])
    {
        $options = array_merge($options, ['command' => $command]);
        return $application->run(new ArrayInput($options));
    }
}
