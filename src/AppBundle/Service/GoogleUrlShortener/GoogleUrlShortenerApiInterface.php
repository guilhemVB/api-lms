<?php

namespace AppBundle\Service\GoogleUrlShortener;

interface GoogleUrlShortenerApiInterface
{
    /**
     * @param string $token
     * @return bool|string
     */
    public function shortenByVoyageToken($token);

    /**
     * @param string $url
     * @return bool|string
     */
    public function shorten($url);

    /**
     * @param string $url
     * @return bool|string
     */
    public function expand($url);
}