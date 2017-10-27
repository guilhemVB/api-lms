<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Behatch\Context\RestContext;

class AuthenticationContext extends CommonContext
{

    /** @var JWTManager */
    private $JWTManager;

    /** @var RestContext */
    private $restContext;

    public function __construct(ContainerInterface $container, JWTManager $JWTManager)
    {
        parent::__construct($container);
        $this->JWTManager = $JWTManager;
    }

    /**
     * @BeforeScenario
     */
    public function getBehatchJsonContext(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->restContext = $environment->getContext('Behatch\Context\RestContext');
    }

    /**
     * @Given I authenticate the user :username
     */
    public function iAuthenticateTheUser($username)
    {
        $user = $this->findUserByName($username);
        $token = $this->JWTManager->create($user);
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer ' . $token);
    }
}
