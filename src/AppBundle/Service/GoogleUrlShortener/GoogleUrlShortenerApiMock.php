<?php

namespace AppBundle\Service\GoogleUrlShortener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class GoogleUrlShortenerApiMock implements GoogleUrlShortenerApiInterface
{

    /**
     * @param string $token
     * @return bool|string
     */
    public function shortenByVoyageToken($token)
    {
        return 'google.com/shortenMOCK';
    }

    /**
     * @param string $url
     * @return bool|string
     */
    public function shorten($url)
    {
        return 'google.com/shortenMOCK';
    }

    /**
     * @param string $url
     * @return bool|string
     */
    public function expand($url)
    {
        return 'lemondeensac.com/toto/MOCK';
    }
}