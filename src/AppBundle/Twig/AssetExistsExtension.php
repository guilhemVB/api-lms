<?php

namespace AppBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;

class AssetExistsExtension extends \Twig_Extension
{

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('asset_exists', array($this, 'assetExist')),
            new \Twig_SimpleFilter('journeyTime', array($this, 'journeyTimePrinter')),
        );
    }

    /**
     * @param string $path
     * @return bool
     */
    public function assetExist($path)
    {
        $webRoot = realpath($this->kernel->getRootDir() . '/../web');
        $toCheck = realpath($webRoot . '/' . $path);

        if (!is_file($toCheck)) {
            return false;
        }

        // check if file is well contained in web/ directory (prevents ../ in paths)
        if (strncmp($webRoot, $toCheck, strlen($webRoot)) !== 0) {
            return false;
        }

        return true;
    }

    public function journeyTimePrinter($timeInMinutes)
    {
        $hours = floor($timeInMinutes / 60);
        $minutes = $timeInMinutes % 60;

        if ($minutes == 0) {
            $minutes = '00';
        } elseif ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        if ($hours > 0) {
            return sprintf("%sh%s", $hours, $minutes);
        } else {
            return sprintf("%smin", $minutes);
        }
    }

    public function getName()
    {
        return 'asset_exist_extension';
    }
}