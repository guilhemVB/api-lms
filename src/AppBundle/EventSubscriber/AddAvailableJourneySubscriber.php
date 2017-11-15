<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Service\CRUD\StageManager;
use AppBundle\Service\CRUD\VoyageManager;
use AppBundle\Service\Journey\JourneyService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AddAvailableJourneySubscriber implements EventSubscriberInterface
{

    /**
     * @var StageManager
     */
    private $stageManager;

    /**
     * @var VoyageManager
     */
    private $voyageManager;


    public function __construct(StageManager $stageManager, VoyageManager $voyageManager)
    {
        $this->stageManager = $stageManager;
        $this->voyageManager = $voyageManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['calculateAvailableJourneys', EventPriorities::POST_WRITE],
        ];
    }

    public function calculateAvailableJourneys(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        $object = $event->getControllerResult();
        if ($method === 'POST' || $method === 'PUT') {
            if ($object instanceof Stage) {
                $this->stageManager->updateAvailableJourneys($object->getVoyage());
            } elseif ($object instanceof Voyage) {
                $this->stageManager->updateAvailableJourneys($object);
            }
        } elseif ($method === 'DELETE') {
            $objectReq = $event->getRequest()->get('data');
            if ($objectReq instanceof Stage) {
                $this->stageManager->updateAvailableJourneys($objectReq->getVoyage());
            }
        }

        $event->setControllerResult($object);
    }
}
