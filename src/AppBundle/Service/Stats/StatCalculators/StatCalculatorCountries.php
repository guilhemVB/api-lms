<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class StatCalculatorCountries implements StatCalculatorInterface
{
    /** @var array */
    private $countries = [];

    public function addStage(Stage $stage)
    {
        if (!is_null($stage->getCountry())) {
            $country = $stage->getCountry();
        } else {
            $country = $stage->getDestination()->getCountry();
        }

        $this->countries[$country->getCodeAlpha3()] =
            isset($this->countries[$country->getCodeAlpha3()]) ?
                $this->countries[$country->getCodeAlpha3()] + 1 :
                1;
    }

    public function addFirstStep(Voyage $voyage)
    {
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return [
            'nbCountries' => count($this->countries),
            'listCountries' => array_keys($this->countries),
        ];
    }
}
