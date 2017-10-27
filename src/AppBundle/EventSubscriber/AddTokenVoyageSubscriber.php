<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Voyage;
use AppBundle\Service\Tokenizer\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddTokenVoyageSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenrator;

    /**
     * @param TokenGeneratorInterface $tokenGenrator
     */
    public function __construct(TokenGeneratorInterface $tokenGenrator)
    {
        $this->tokenGenrator = $tokenGenrator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['generateToken', EventPriorities::PRE_WRITE],
        ];
    }

    public function generateToken(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if ($method === 'POST') {
            $object = $event->getControllerResult();
            if ($object instanceof Voyage) {
                $object->setToken($this->tokenGenrator->generate());
                $event->setControllerResult($object);
            }
        }
    }
}
