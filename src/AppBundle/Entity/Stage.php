<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ApiResource(
 *      attributes={
 *          "force_eager"=false,
 *          "normalization_context"={"groups"={"read-stage", "journey", "read-destination-light", "read-country-light", "availableJourney"}},
 *          "denormalization_context"={"groups"={"write-stage", "journey"}}
 *      },
 * )
 * @ORM\Table(name="stage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StageRepository")
 */
class Stage implements JourneyInterface
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
     * @Gedmo\SortableGroup
     * @Groups({"write-stage"})
     * @Assert\NotNull()
     */
    private $voyage;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     * @Groups({"read-stage", "write-stage"})
     * @Assert\NotNull()
     * @Assert\Range(min=0)
     */
    private $nbDays;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination")
     * @ORM\JoinColumn(name="destination_id", referencedColumnName="id", nullable=true)
     * @Groups({"read-stage", "write-stage"})
     * @Assert\Expression(
     *     expression="this.assertCountryXORDestination()",
     *     message="Either Country or Destination must be set"
     * )
     */
    private $destination;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=true)
     * @Groups({"read-stage", "write-stage"})
     */
    private $country;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"read-stage", "write-stage"})
     * @Gedmo\SortablePosition
     * @Assert\Range(min=0)
     * @Assert\NotNull()
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
        $this->voyage->addStage($this);

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

    /**
     * @return bool
     */
    public function assertCountryXORDestination()
    {
        return $this->getCountry() !== null xor $this->getDestination() !== null;
    }
}
