<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class StatCalculatorNumberDays implements StatCalculatorInterface
{
    /** @var int */
    private $nbDays = 0;


    public function addStage(Stage $stage)
    {
        $this->nbDays += $stage->getNbDays();
    }

    public function addFirstStep(Voyage $voyage)
    {
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['nbDays' => $this->nbDays];
    }
}
