<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class StatCalculatorDestinations implements StatCalculatorInterface
{
    /** @var array */
    private $stagesWithNbDays = [];

    /** @var int */
    private $nbDestinationToReturn;

    public function __construct($nbDestinationToReturn = 1)
    {
        $this->nbDestinationToReturn = $nbDestinationToReturn;
    }

    public function addFirstStep(Voyage $voyage)
    {
        $this->stagesWithNbDays[] = [
            'destination' => $voyage->getStartDestination(),
            'nbDays'      => 0,
        ];
    }

    public function addStage(Stage $stage)
    {
        foreach ($this->stagesWithNbDays as &$stageWithNbDays) {

            if (isset($stageWithNbDays['destination'])) {
                /** @var Destination $destination */
                $destination = $stageWithNbDays['destination'];
                if (!is_null($stage->getDestination()) && $destination->getId() == $stage->getDestination()->getId()) {
                    $stageWithNbDays['nbDays'] += $stage->getNbDays();
                    return;
                }
            } elseif (isset($stageWithNbDays['country'])) {
                /** @var Country $country */
                $country = $stageWithNbDays['country'];
                if (!is_null($stage->getCountry()) && $country->getId() == $stage->getCountry()->getId()) {
                    $stageWithNbDays['nbDays'] += $stage->getNbDays();
                    return;
                }

            }
        }

        if (!is_null($stage->getDestination())) {
            $this->stagesWithNbDays[] = [
                'destination' => $stage->getDestination(),
                'nbDays'      => $stage->getNbDays(),
            ];
        } elseif (!is_null($stage->getCountry())) {
            $this->stagesWithNbDays[] = [
                'country' => $stage->getCountry(),
                'nbDays'  => $stage->getNbDays(),
            ];
        }
    }

    /**
     * @return array
     */
    public function getStats()
    {
        usort($this->stagesWithNbDays, function ($a, $b) {
            return $b['nbDays'] - $a['nbDays'];
        });

        $data = [];
        if ($this->nbDestinationToReturn == 1) {
            if (isset($this->stagesWithNbDays[0])) {
                /** @var Destination|Country $destinationOrCountry */
                if (isset($this->stagesWithNbDays[0]['destination'])) {
                    $destinationOrCountry = $this->stagesWithNbDays[0]['destination'];
                    $type = 'destination';
                } else {
                    $destinationOrCountry = $this->stagesWithNbDays[0]['country'];
                    $type = 'country';
                }
                $data = [
                    'id'   => $destinationOrCountry->getId(),
                    'name' => $destinationOrCountry->getName(),
                    'slug' => $destinationOrCountry->getSlug(),
                    'type' => $type,
                ];
            }

            return ['mainDestination' => $data];
        } elseif ($this->nbDestinationToReturn >= 2) {
            for ($i = 0; $i < $this->nbDestinationToReturn; $i++) {
                if (isset($this->stagesWithNbDays[$i])) {
                    /** @var Destination|Country $destinationOrCountry */
                    if (isset($this->stagesWithNbDays[$i]['destination'])) {
                        $destinationOrCountry = $this->stagesWithNbDays[$i]['destination'];
                        $type = 'destination';
                    } else {
                        $destinationOrCountry = $this->stagesWithNbDays[$i]['country'];
                        $type = 'country';
                    }
                    $data[] = [
                        'id'   => $destinationOrCountry->getId(),
                        'name' => $destinationOrCountry->getName(),
                        'slug' => $destinationOrCountry->getSlug(),
                        'type' => $type,
                    ];
                }
            }

            return ['mainDestinations' => $data];
        }

    }
}
