<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 */
class Currency
{

    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Groups({"currency"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Groups({"currency"})
     */
    private $code;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"currency"})
     */
    private $eurRate;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $usdRate;

    /**
     * @var ArrayCollection|Destination[]
     * @ORM\OneToMany(targetEntity="Country", mappedBy="currency")
     */
    private $countries;

    function __construct()
    {
        $this->countries = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getEurRate()
    {
        return $this->eurRate;
    }

    /**
     * @param float $eurRate
     * @return $this
     */
    public function setEurRate($eurRate)
    {
        $this->eurRate = $eurRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getUsdRate()
    {
        return $this->usdRate;
    }

    /**
     * @param float $usdRate
     * @return $this
     */
    public function setUsdRate($usdRate)
    {
        $this->usdRate = $usdRate;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param Country $country
     * @return Currency
     */
    public function addCountry(Country $country)
    {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * @param Country $country
     */
    public function removeCountry(Country $country)
    {
        $this->countries->removeElement($country);
    }

    /**
     * @return ArrayCollection|Country[]
     */
    public function getCountries()
    {
        return $this->countries;
    }

}
