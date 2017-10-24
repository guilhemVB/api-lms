<?php

namespace AppBundle\Service\GoogleUrlShortener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class GoogleUrlShortenerApi implements GoogleUrlShortenerApiInterface
{

    /** @var string */
    private $apiURL;
    /**
     * @var Router
     */
    private $router;
    private $key;

    public function __construct($key, Router $router, $apiURL = 'https://www.googleapis.com/urlshortener/v1/url')
    {
        $this->apiURL = $apiURL . '?key=' . $key;
        $this->router = $router;
        $this->key = $key;
    }

    /**
     * @param string $token
     * @return bool|string
     */
    public function shortenByVoyageToken($token)
    {
        //TODO
        return '';
//        $url = $this->router->generate('shareVoyage', ['token' => $token], Router::ABSOLUTE_URL);
//
//        return $this->shorten($url);
    }

    /**
     * @param string $url
     * @return bool|string
     */
    public function shorten($url)
    {
        $response = $this->send($url);

        return isset($response['id']) ? $response['id'] : false;
    }

    /**
     * @param string $url
     * @return bool|string
     */
    public function expand($url)
    {
        $response = $this->send($url, false);

        return isset($response['longUrl']) ? $response['longUrl'] : false;
    }

    /**
     * @param string $url
     * @param bool $shorten
     * @return array|null
     */
    private function send($url, $shorten = true)
    {
        $ch = curl_init();
        if ($shorten) {
            curl_setopt($ch, CURLOPT_URL, $this->apiURL);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $url, 'key' => $this->key)));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->apiURL . '&shortUrl=' . $url);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}