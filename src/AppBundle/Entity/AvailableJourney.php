<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={"get"={"method"="GET"}},
 *      itemOperations={"get"={"method"="GET"}},
 *      attributes={
 *          "force_eager"=false,
 *          "filters"={"availableJourney.group_filter", "availableJourney.search_filter"},
 *          "normalization_context"={"groups"={"availableJourney"}}
 *      },
 * )
 * @ORM\Table(name="available_journey")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvailableJourneyRepository")
 */
class AvailableJourney
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"availableJourney"})
     */
    private $id;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination")
     * @Groups({"availableJourney"})
     */
    private $fromDestination;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination")
     * @Groups({"availableJourney"})
     */
    private $toDestination;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"availableJourney"})
     */
    private $flyPrices;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"availableJourney"})
     */
    private $flyTime;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"availableJourney"})
     */
    private $busPrices;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"availableJourney"})
     */
    private $busTime;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"availableJourney"})
     */
    private $trainPrices;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"availableJourney"})
     */
    private $trainTime;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Destination
     */
    public function getFromDestination()
    {
        return $this->fromDestination;
    }

    /**
     * @param Destination $fromDestination
     * @return $this
     */
    public function setFromDestination($fromDestination)
    {
        $this->fromDestination = $fromDestination;

        return $this;
    }

    /**
     * @return Destination
     */
    public function getToDestination()
    {
        return $this->toDestination;
    }

    /**
     * @param Destination $toDestination
     * @return $this
     */
    public function setToDestination($toDestination)
    {
        $this->toDestination = $toDestination;

        return $this;
    }

    /**
     * @return float
     */
    public function getFlyPrices()
    {
        return $this->flyPrices;
    }

    /**
     * @param float $flyPrices
     * @return $this
     */
    public function setFlyPrices($flyPrices)
    {
        $this->flyPrices = $flyPrices;

        return $this;
    }

    /**
     * @return int
     */
    public function getFlyTime()
    {
        return $this->flyTime;
    }

    /**
     * @param int $flyTime
     * @return $this
     */
    public function setFlyTime($flyTime)
    {
        $this->flyTime = $flyTime;

        return $this;
    }

    /**
     * @return float
     */
    public function getBusPrices()
    {
        return $this->busPrices;
    }

    /**
     * @param float $busPrices
     * @return $this
     */
    public function setBusPrices($busPrices)
    {
        $this->busPrices = $busPrices;

        return $this;
    }

    /**
     * @return int
     */
    public function getBusTime()
    {
        return $this->busTime;
    }

    /**
     * @param int $busTime
     * @return $this
     */
    public function setBusTime($busTime)
    {
        $this->busTime = $busTime;

        return $this;
    }

    /**
     * @return float
     */
    public function getTrainPrices()
    {
        return $this->trainPrices;
    }

    /**
     * @param float $trainPrices
     * @return $this
     */
    public function setTrainPrices($trainPrices)
    {
        $this->trainPrices = $trainPrices;

        return $this;
    }

    /**
     * @return int
     */
    public function getTrainTime()
    {
        return $this->trainTime;
    }

    /**
     * @param int $trainTime
     * @return $this
     */
    public function setTrainTime($trainTime)
    {
        $this->trainTime = $trainTime;

        return $this;
    }

}
