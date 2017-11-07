<?php

namespace AppBundle\Service\Journey;

use AppBundle\Entity\Destination;
use Symfony\Component\HttpKernel\Kernel;

class JourneyFetcherMock implements JourneyFetcherInterface
{

    /**
     * @var Kernel
     */
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param Destination $fromDestination
     * @param Destination $toDestination
     * @return array
     * @throws \Exception
     */
    public function fetch(Destination $fromDestination, Destination $toDestination)
    {
        $fileName = $fromDestination->getSlug() . '-' . $toDestination->getSlug();
        $file = file_get_contents( $this->kernel->getProjectDir() . sprintf("/features/bootstrap/AppBundle/data/%s.json", $fileName));

        if ($file) {
            return ['data' => json_decode($file, true), 'url' => $fileName];
        } else {
            throw new \Exception("$fileName doesn't exist in the mock.");
        }
    }
}