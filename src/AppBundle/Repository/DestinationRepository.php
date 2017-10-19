<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Destination;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 * @method Destination findOneByName($name)
 * @method Destination find($id)
 */
class DestinationRepository extends EntityRepository
{

    /**
     * @param int $nbDestination
     * @return Destination[]
     */
    public function findLastCompleteDestinations($nbDestination = 3)
    {
        return $this->findBy(['isPartial' => false], ['completedAt' => 'DESC'], $nbDestination);
    }


    /**
     * @return array
     */
    public function findAddDestinationsIdsAndNames()
    {
        $qb = $this->createQueryBuilder('destination')
            ->select('destination.id, destination.name');
        try {
            return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
        } catch (\Exception $e) {
            return [];
        }
    }
}
