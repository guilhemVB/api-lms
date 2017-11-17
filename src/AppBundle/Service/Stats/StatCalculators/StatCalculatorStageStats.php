<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Service\Tools\DestinationPeriods;
use AppBundle\Entity\Voyage;

class StatCalculatorStageStats implements StatCalculatorInterface
{

    /** @var  array */
    private $stagesStats = [];

    /** @var \DateTime|null */
    private $dateFrom;

    /** @var \DateTime */
    private $dateTo;

    public function __construct()
    {
        $this->dateFrom = null;
        $this->dateTo = null;
    }


    public function addStage(Stage $stage)
    {
        if (is_null($this->dateTo)) {
            $this->dateTo = $stage->getVoyage()->getStartDate();
        }

        $this->dateFrom = $this->dateTo;
        $this->dateTo = clone $this->dateFrom;
        $this->dateTo->add(new \DateInterval('P' . $stage->getNbDays() . 'D'));

        $destination = !is_null($stage->getDestination()) ? $stage->getDestination() : $stage->getCountry()->getDefaultDestination();

        $nbStars = $this->extractNbStart($this->dateFrom, $this->dateTo, $destination);
        $this->stagesStats[$stage->getId()] = [
            'stage' => ['id' => $stage->getId(), 'nbDays' => $stage->getNbDays(), 'position' => $stage->getPosition()],
            'dateFrom'         => $this->dateFrom,
            'dateTo'           => $this->dateTo,
            'nbStars'          => $nbStars,
        ];
    }

    public function addFirstStep(Voyage $voyage)
    {
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['stagesStats' => $this->stagesStats];
    }

    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param Destination $destination
     * @return int
     */
    private function extractNbStart(\DateTime $dateFrom, \DateTime $dateTo, Destination $destination)
    {
        $month = $this->extractMonthFromDates($dateFrom, $dateTo);
        switch ($month) {
            case 'january' :
                return $destination->getPeriodJanuary();
            case 'february' :
                return $destination->getPeriodFebruary();
            case 'march' :
                return $destination->getPeriodMarch();
            case 'april' :
                return $destination->getPeriodApril();
            case 'may' :
                return $destination->getPeriodMay();
            case 'june' :
                return $destination->getPeriodJune();
            case 'july' :
                return $destination->getPeriodJuly();
            case 'august' :
                return $destination->getPeriodAugust();
            case 'september' :
                return $destination->getPeriodSeptember();
            case 'october' :
                return $destination->getPeriodOctober();
            case 'november' :
                return $destination->getPeriodNovember();
            case 'december' :
                return $destination->getPeriodDecember();
        }

        return 0;
    }

    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return string
     */
    private function extractMonthFromDates(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $monthFrom = (int)($dateFrom->format('m'));
        $monthTo = (int)($dateTo->format('m'));

        if ($monthFrom === $monthTo) {
            return DestinationPeriods::getCorrelationFromMonthNumberToMonthName($monthFrom);
        }

        // more than 2 months
        if ($monthFrom + 1 !== $monthTo) {
            return DestinationPeriods::getCorrelationFromMonthNumberToMonthName($monthFrom + 1);
        }

        $nbDaysInFirstMonth = 30 - (int)($dateFrom->format('d'));
        $nbDaysInSecondMonth = (int)($dateTo->format('d'));

        return DestinationPeriods::getCorrelationFromMonthNumberToMonthName($nbDaysInFirstMonth > $nbDaysInSecondMonth ? $monthFrom : $monthTo);
    }
}
