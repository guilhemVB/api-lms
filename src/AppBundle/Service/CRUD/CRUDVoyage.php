<?php

namespace AppBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use AppBundle\Entity\User;
use AppBundle\Entity\Voyage;
use AppBundle\Service\GoogleUrlShortener\GoogleUrlShortenerApiInterface;
use AppBundle\Service\Journey\JourneyService;
use Doctrine\ORM\EntityManager;

class CRUDVoyage
{

    /** @var EntityManager */
    private $em;

    /** @var GoogleUrlShortenerApiInterface */
    private $googleUrlShortenerApi;

    /** @var JourneyService */
    private $journeyService;

    public function __construct(EntityManager $em, GoogleUrlShortenerApiInterface $googleUrlShortenerApi, JourneyService $journeyService)
    {
        $this->em = $em;
        $this->googleUrlShortenerApi = $googleUrlShortenerApi;
        $this->journeyService = $journeyService;
    }


    /**
     * @param User $user
     * @param string $name
     * @param string $deparatureDate
     * @param Destination $destination
     * @return Voyage
     */
    public function add(User $user, $name, $deparatureDate, $destination)
    {
        $voyage = new Voyage();
        $voyage->setUser($user);
        $voyage->setName($name);
        $voyage->setStartDate(new \DateTime($deparatureDate));
        $voyage->setStartDestination($destination);

        $urlMinified = $this->googleUrlShortenerApi->shortenByVoyageToken($voyage->getToken());
        $voyage->setUrlMinified($urlMinified);

        $this->em->persist($voyage);
        $this->em->flush();

        return $voyage;
    }


    /**
     * @param Voyage $voyage
     * @param string $name
     * @param string $deparatureDate
     * @param Destination $destination
     * @return Voyage
     */
    public function update(Voyage $voyage, $name, $deparatureDate, $destination)
    {
        $voyage->setName($name);
        $voyage->setStartDestination($destination);
        $voyage->setStartDate(new \DateTime($deparatureDate));

        $this->em->persist($voyage);
        $this->em->flush();

        $this->journeyService->updateJourneyByVoyage($voyage);

        return $voyage;
    }

    /**
     * @param Voyage $voyage
     * @param string $transportType
     */
    public function changeTransportType(Voyage $voyage, $transportType)
    {
        $voyage->setTransportType($transportType);
        $this->em->persist($voyage);
        $this->em->flush();
    }

}
