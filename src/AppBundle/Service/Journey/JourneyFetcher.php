<?php

namespace AppBundle\Service\Journey;

use AppBundle\Entity\Destination;
use Psr\Log\LoggerInterface;

class JourneyFetcher implements JourneyFetcherInterface
{

    /** @var string */
    private $apiUrl;

    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl . "&oPos=%s,%s&dPos=%s,%s";
    }

    /**
     * @param Destination $fromDestination
     * @param Destination $toDestination
     * @return array
     */
    public function fetch(Destination $fromDestination, Destination $toDestination)
    {
        $url = sprintf($this->apiUrl, $fromDestination->getLatitude(), $fromDestination->getLongitude(), $toDestination->getLatitude(), $toDestination->getLongitude());
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        sleep(rand(1, 2));

        return ['data' => json_decode($result, true), 'url' => $url];
    }
}
