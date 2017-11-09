<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Service\Journey\JourneyService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AddAvailableJourneySubscriber implements EventSubscriberInterface
{

    /**
     * @var JourneyService
     */
    private $journeyService;


    public function __construct(JourneyService $journeyService)
    {
        $this->journeyService = $journeyService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['calculateAvailableJourneys', EventPriorities::PRE_WRITE],
        ];
    }

    public function calculateAvailableJourneys(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if ($method === 'POST') {
            $object = $event->getControllerResult();
            if ($object instanceof Stage) {

                // utiliser le CRUDStage Add

                $event->setControllerResult($object);
            } elseif ($object instanceof Voyage) {
                // TODO
            }
        }
    }
}
