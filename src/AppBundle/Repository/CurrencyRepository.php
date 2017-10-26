<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Currency;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Entity
 * @method Currency findOneByCode($code)
 */
class CurrencyRepository extends EntityRepository
{

}
