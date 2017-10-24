<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Voyage;
use AppBundle\Service\GoogleUrlShortener\GoogleUrlShortenerApiInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddGoogleUrlShortenerSubscriber implements EventSubscriberInterface
{

    /** @var GoogleUrlShortenerApiInterface */
    private $googleUrlShortenerApi;

    public function __construct(GoogleUrlShortenerApiInterface $googleUrlShortenerApi)
    {
        $this->googleUrlShortenerApi = $googleUrlShortenerApi;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setGoogleUrlShortener', EventPriorities::PRE_WRITE],
        ];
    }

    public function setGoogleUrlShortener(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if ($method === 'POST' || $method === 'PUT') {
            $object = $event->getControllerResult();
            if ($object instanceof Voyage) {

                $urlMinified = $this->googleUrlShortenerApi->shortenByVoyageToken($object->getToken());
                $object->setUrlMinified($urlMinified);

                $event->setControllerResult($object);
            }
        }
    }
}