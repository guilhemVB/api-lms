<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class StatCalculatorDates implements StatCalculatorInterface
{
    /** @var int */
    private $nbDays = 0;

    /** @var  \DateTime|null */
    private $startDate = null;

    public function addStage(Stage $stage)
    {
        if (is_null($this->startDate)) {
            $this->startDate = $stage->getVoyage()->getStartDate();
        }
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
        $endDate = null;
        if (!is_null($this->startDate)) {
            $endDate = clone $this->startDate;
            $endDate->add(new \DateInterval('P' . $this->nbDays . 'D'));
        }

        return [
            'startDate' => $this->startDate,
            'endDate'   => $endDate,
        ];
    }
}
