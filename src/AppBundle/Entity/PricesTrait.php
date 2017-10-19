<?php

namespace AppBundle\Entity;

trait PricesTrait
{

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceAccommodation;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceLifeCost;

    /**
     * @return int
     */
    public function getPriceAccommodation()
    {
        return $this->priceAccommodation;
    }

    /**
     * @param int $priceAccommodation
     * @return $this
     */
    public function setPriceAccommodation($priceAccommodation)
    {
        $this->priceAccommodation = $priceAccommodation;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceLifeCost()
    {
        return $this->priceLifeCost;
    }

    /**
     * @param int $priceLifeCost
     * @return $this
     */
    public function setPriceLifeCost($priceLifeCost)
    {
        $this->priceLifeCost = $priceLifeCost;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPrices()
    {
        return $this->priceLifeCost + $this->priceAccommodation;
    }


}
