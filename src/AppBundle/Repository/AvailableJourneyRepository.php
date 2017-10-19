<?php

namespace AppBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 */
class AvailableJourneyRepository extends EntityRepository
{

    /**
     * @param int $fromDestinationId
     * @param int $toDestinationId
     * @return array
     */
    public function findByFromDestinationIdAndToDestinationId($fromDestinationId, $toDestinationId)
    {
        $qb = $this->createQueryBuilder('availableJourney')
            ->select('availableJourney.id')
            ->leftJoin('availableJourney.fromDestination', 'fromDestination')
            ->leftJoin('availableJourney.toDestination', 'toDestination')
            ->where('fromDestination.id = :fromDestinationId')
            ->andWhere('toDestination.id = :toDestinationId')
            ->setParameter('fromDestinationId', $fromDestinationId)
            ->setParameter('toDestinationId', $toDestinationId);
        try {
            return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
        } catch (\Exception $e) {
            return [];
        }
    }
}
