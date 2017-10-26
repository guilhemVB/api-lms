<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use Doctrine\ORM\Tools\SchemaTool;

class FeatureContext extends CommonContext implements Context
{
    /** @var SchemaTool */
    private $schemaTool;

    /** @var array */
    private $classes;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->schemaTool = new SchemaTool($this->em);
        $this->classes = $this->em->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->dropSchema($this->classes);
    }

    /** @BeforeScenario */
    public function before()
    {
        $this->schemaTool->createSchema($this->classes);
    }

    /**
     * @AfterScenario
     */
    public function dropDatabase()
    {
        $this->schemaTool->dropSchema($this->classes);
    }
}
