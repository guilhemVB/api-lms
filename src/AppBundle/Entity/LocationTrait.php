<?php

namespace AppBundle\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

trait LocationTrait
{
    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"location"})
     */
    private $longitude;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"location"})
     */
    private $latitude;

    /**
     * @param float $longitude
     * @return Destination
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $latitude
     * @return Destination
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }


}
