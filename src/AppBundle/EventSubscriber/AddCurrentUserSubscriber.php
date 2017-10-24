<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Voyage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddCurrentUserSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {

        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setCurrentUser', EventPriorities::PRE_WRITE],
        ];
    }

    public function setCurrentUser(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if ($method === 'POST') {
            $object = $event->getControllerResult();
            if ($object instanceof Voyage) {
                $object->setUser($this->tokenStorage->getToken()->getUser());
                $event->setControllerResult($object);
            }
        }
    }
}