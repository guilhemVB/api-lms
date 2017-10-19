<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"read-stage", "journey", "read-destination-light", "read-country-light", "read-voyage", "availableJourney"}}
 *      },
 * )
 * @ORM\Table(name="stage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StageRepository")
 */
class Stage
{

    use JourneyTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read-stage"})
     */
    private $id;

    /**
     * @var Voyage
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Voyage", inversedBy="stages")
     * @Groups({"read-stage"})
     */
    private $voyage;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination")
     * @ORM\JoinColumn(name="destination_id", referencedColumnName="id", nullable=true)
     * @Groups({"read-stage"})
     */
    private $destination;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     * @Groups({"read-stage"})
     */
    private $nbDays;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=true)
     * @Groups({"read-stage"})
     */
    private $country;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"read-stage"})
     */
    private $position;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param Voyage $voyage
     * @return Stage
     */
    public function setVoyage(Voyage $voyage = null)
    {
        $this->voyage = $voyage;

        return $this;
    }

    /**
     * @return Voyage
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * @param Destination $destination
     * @return Stage
     */
    public function setDestination(Destination $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return Destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param int $position
     * @return Stage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param float $nbDays
     * @return Stage
     */
    public function setNbDays($nbDays)
    {
        $this->nbDays = $nbDays;

        return $this;
    }

    /**
     * @return float
     */
    public function getNbDays()
    {
        return $this->nbDays;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
