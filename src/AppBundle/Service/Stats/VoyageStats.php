<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorCountries;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorCrowFliesDistance;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorDates;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorDestinations;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorInterface;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorStageStats;

class VoyageStats
{

    /**
     * @param Voyage $voyage
     * @param Stage[] $stagesSorted
     * @param StatCalculatorInterface[] $statCalculators
     * @return array
     */
    public function calculate(Voyage $voyage, $stagesSorted, $statCalculators)
    {
        foreach ($statCalculators as $statCalculator) {
            $statCalculator->addFirstStep($voyage);
            foreach ($stagesSorted as $stage) {
                $statCalculator->addStage($stage);
            }
        }

        $stats = [
            'nbStages' => count($stagesSorted)
        ];

        foreach ($statCalculators as $statCalculator) {
            $stats = array_merge($stats, $statCalculator->getStats());
        }

        return $stats;
    }

    /**
     * @param Voyage $voyage
     * @param Stage[] $stagesSorted
     * @return array
     */
    public function calculateAllStats(Voyage $voyage, $stagesSorted)
    {
        $statCalculators = [
            new StatCalculatorCountries(),
            new StatCalculatorCrowFliesDistance(),
            new StatCalculatorDates(),
            new StatCalculatorNumberDays(),
            new StatCalculatorPrices(),
            new StatCalculatorStageStats(),
            new StatCalculatorDestinations(),
        ];

        return $this->calculate($voyage, $stagesSorted, $statCalculators);
    }

}
