<?php

namespace AppBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class UserContext extends CommonContext
{
    /** @var UserManagerInterface */
    private $userManager;

    /** @var KernelInterface */
    private $kernel;

    public function __construct(ContainerInterface $container, KernelInterface $kernel)
    {
        parent::__construct($container);
        $this->userManager = $container->get('fos_user.user_manager');
        $this->kernel = $kernel;
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
        }
    }

    /**
     * @When I ask the token for user :username password :password
     */
    public function iAskTheTokenForUserPassword($username, $password)
    {
        $response = $this->kernel->handle(Request::create(
            '/login_check',
            'POST',
            [
                'username' => $username,
                'password' => $password,
            ]
        ));

        $response = $response->getContent();
        $responseDecoded = json_decode($response, true);

        $this->userToken = $responseDecoded['token'];
    }
}
