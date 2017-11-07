<?php

namespace AppBundle\Service;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;

class MaplaceMarkerBuilder
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    private function defaultOptions()
    {
        return [
            'disableHtml' => false,
            'disableZoom' => false,
            'ordereIcons' => false,
            'addDefaultZoom' => false,
            'forceMarkerIcon' => false
        ];
    }

    /**
     * @param Country $country
     * @param array $options
     * @param null|int $number
     * @return array
     */
    public function buildMarkerFromCountry(Country $country, $options = [], $number = null)
    {
        return $this->buildMaker($country->getLongitude(),
            $country->getLatitude(),
            $country->getName(),
            null, $country,
            $options, $number, false);
    }

    /**
     * @param Destination $destination
     * @param array $options
     * @param null|int $number
     * @return array
     */
    public function buildMarkerFromDestination(Destination $destination, $options = [], $number = null)
    {
        return $this->buildMaker($destination->getLongitude(),
            $destination->getLatitude(),
            $destination->getName(),
            $destination, null,
            $options, $number, $destination->isPartial());
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @param string $name
     * @param Destination|null $destination
     * @param Country|null $country
     * @param array $options
     * @param null|int $number
     * @param bool $onlyPoint
     * @return array
     */
    private function buildMaker($longitude, $latitude, $name, Destination $destination = null, Country $country = null, $options = [], $number = null, $onlyPoint = false)
    {
        $options = array_merge($this->defaultOptions(), $options);
        $dataMaplace = [
            'lon'   => $longitude,
            'lat'   => $latitude,
            'title' => $name,
        ];

        if (!$options['disableHtml']) {
            if (!is_null($destination)) {
                $dataMaplace['html'] = $this->twig->render('AppBundle:Destination:googleMarkerDestination.html.twig', ['destination' => $destination]);
                $dataMaplace['id'] = $destination->getId();
            } elseif (!is_null($country)) {
                $dataMaplace['html'] = $this->twig->render('AppBundle:Country:googleMarkerCountry.html.twig', ['country' => $country]);
                $dataMaplace['id'] = $country->getId();
            }
        }

        if (!$options['disableZoom']) {
            $dataMaplace['zoom'] = 12;
        } elseif ($options['addDefaultZoom']) {
            $dataMaplace['zoom'] = 7;
        }

        if ($options['forceMarkerIcon']) {
            $dataMaplace['icon'] = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
        } elseif ($options['ordereIcons'] && !is_null($number)) {
            $iconLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

            $number = $number % 26;

            $dataMaplace['icon'] = 'http://maps.google.com/mapfiles/marker' . $iconLetters[$number] . '.png';
        } elseif ($onlyPoint) {
            $dataMaplace['icon'] = 'http://labs.google.com/ridefinder/images/mm_20_orange.png';
        } else {
            $dataMaplace['icon'] = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
        }

        return $dataMaplace;
    }

    /**
     * @param Country[] $countries
     * @param array $options
     * @return array
     */
    public function buildMarkerFromCountries($countries, $options = [])
    {
        $dataMaplace = [];
        foreach ($countries as $country) {
            $dataMaplace[] = $this->buildMarkerFromCountry($country, $options);
        }

        return $dataMaplace;
    }

    /**
     * @param Destination[] $destinations
     * @param array $options
     * @return array
     */
    public function buildMarkerFromDestinations($destinations, $options = [])
    {
        $dataMaplace = [];
        $number = 0;
        foreach ($destinations as $destination) {
            $dataMaplace[] = $this->buildMarkerFromDestination($destination, $options, $number);
            $number++;
        }

        return $dataMaplace;
    }

    /**
     * @param Stage[] $stages
     * @param array $options
     * @return array
     */
    public function buildMarkerFromStages($stages, $options = [])
    {
        $destinations = [];
        foreach ($stages as $stage) {
            $destinations[] = $stage->getDestination();
        }
        return $this->buildMarkerFromDestinations($destinations, $options);
    }

}
