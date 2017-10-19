<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Country;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Entity
 * @method Country findOneByName(string)
 * @method Country find($id)
 */
class CountryRepository extends EntityRepository
{

    /**
     * @return Country[]
     */
    public function findCountriesWithDestinations()
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.destinations', 'd')
            ->addSelect('d')
            ->orderBy('c.name')
            ->addGroupBy('d.id')
            ->addGroupBy('d.name')
            ->addGroupBy('d.isTheCapital')
            ->addGroupBy('d.description')
            ->addGroupBy('d.tips')
            ->addGroupBy('d.longitude')
            ->addGroupBy('d.latitude')
            ->addGroupBy('d.priceAccommodation')
            ->addGroupBy('d.priceLifeCost')
            ->addGroupBy('d.periodJanuary')
            ->addGroupBy('d.periodDecember')
            ->addGroupBy('d.periodNovember')
            ->addGroupBy('d.periodOctober')
            ->addGroupBy('d.periodSeptember')
            ->addGroupBy('d.periodAugust')
            ->addGroupBy('d.periodJuly')
            ->addGroupBy('d.periodJune')
            ->addGroupBy('d.periodMay')
            ->addGroupBy('d.periodApril')
            ->addGroupBy('d.periodMarch')
            ->addGroupBy('d.periodFebruary')
            ->addGroupBy('d.createdAt')
            ->addGroupBy('d.updatedAt')
            ->addGroupBy('d.completedAt')
            ->addGroupBy('d.slug')
            ->addGroupBy('d.country')
            ->addGroupBy('d.isPartial')
            ->addGroupBy('c.id')
            ->addGroupBy('c.codeAlpha2')
            ->addGroupBy('c.codeAlpha3')
            ->addGroupBy('c.name')
            ->addGroupBy('c.capitalName')
            ->addGroupBy('c.currency')
            ->addGroupBy('c.visaInformation')
            ->addGroupBy('c.visaDuration')
            ->addGroupBy('c.languages')
            ->addGroupBy('c.population')
            ->addGroupBy('c.redirectToDestination')
            ->addGroupBy('c.longitude')
            ->addGroupBy('c.latitude')
            ->addGroupBy('c.createdAt')
            ->addGroupBy('c.updatedAt')
            ->addGroupBy('c.slug')
            ->addGroupBy('c.name');

        return $qb->getQuery()->getResult();
    }
}
