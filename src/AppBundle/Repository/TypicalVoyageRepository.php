<?php

namespace AppBundle\Repository;

use AppBundle\Entity\TypicalVoyage;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 * @method TypicalVoyage findOneByVoyage($voyage)
 */
class TypicalVoyageRepository extends EntityRepository
{

    /**
     * @return TypicalVoyage[]
     */
    public function findAllTypicalVoyages()
    {
        $qb = $this->createQueryBuilder('typicalVoyage')
            ->select('typicalVoyage')
            ->leftJoin('typicalVoyage.voyage', 'voyage');
        try {
            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            return [];
        }
    }
}
