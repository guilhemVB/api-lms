<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Behatch\Context\RestContext;

class UserContext extends CommonContext
{
    /** @var UserManagerInterface */
    private $userManager;

    /** @var JWTManager */
    private $JWTManager;

    /** @var RestContext */
    private $restContext;

    public function __construct(ContainerInterface $container, JWTManager $JWTManager)
    {
        parent::__construct($container);
        $this->userManager = $container->get('fos_user.user_manager');
        $this->JWTManager = $JWTManager;
    }

    /**
     *  @BeforeScenario
     */
    public function getBehatchJsonContext(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->restContext = $environment->getContext('Behatch\Context\RestContext');
    }

    private function setToken(UserInterface $user)
    {
        $token = $this->JWTManager->create($user);
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer '.$token);
    }

    /**
     * @Given les utilisateurs :
     */
    public function lesUtilisateurs(TableNode $tableUsers)
    {
        foreach ($tableUsers as $userRow) {
            $name = $userRow['nom'];
            $password = isset($userRow['mot de passe']) ? $userRow['mot de passe'] : $name;
            $email = isset($userRow['email']) ? $userRow['email'] : 'guilhem@guilhem.com';

            $user = $this->userManager->createUser();
            $user->setUsername($name);
            $user->setPlainPassword($password);
            $user->setEmail($email);
            $user->setEnabled(true);

            if (isset($userRow['role'])) {
                $user->addRole($userRow['role']);
            }
            $this->userManager->updateUser($user);
            $this->setToken($user);
            return;
        }
    }
}
