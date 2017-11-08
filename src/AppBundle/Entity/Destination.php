<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={"get"={"method"="GET"}},
 *      itemOperations={"get"={"method"="GET"}},
 *      attributes={
 *          "filters"={"slug.search_filter", "country.search_filter"},
 *          "normalization_context"={"groups"={"read-destination-full", "read-country-light", "prices", "location", "periods"}}
 *      }
 * )
 * @ORM\Table(
 *  name="destination",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="name_unique_by_country", columns={"name", "country_id"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DestinationRepository")
 * @UniqueEntity("slug")
 */
class Destination
{
    use ORMBehaviors\Sluggable\Sluggable;
    use ORMBehaviors\Timestampable\Timestampable;
    use PricesTrait;
    use PeriodsTrait;
    use LocationTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read-destination-light", "read-destination-full"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"read-destination-light", "read-destination-full"})
     */
    private $name;

    /**
     * @var string
     * @Groups({"read-destination-light", "read-destination-full"})
     */
    protected $slug;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isTheCapital = false;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPartial;

    /**
     * @var array
     * @ORM\Column(name="description", type="array", nullable=true)
     * @Groups({"read-destination-full"})
     */
    private $description;

    /**
     * @var array
     * @ORM\Column(name="tips", type="array")
     * @Groups({"read-destination-full"})
     */
    private $tips;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="destinations")
     * @Groups({"read-destination-full"})
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $completedAt;


    public function getSluggableFields()
    {
        return ['name'];
    }

    public function getRegenerateSlugOnUpdate()
    {
        return false;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Destination
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isTheCapital()
    {
        return $this->isTheCapital;
    }

    /**
     * @param bool $isTheCapital
     * @return $this
     */
    public function setIsTheCapital($isTheCapital)
    {
        $this->isTheCapital = $isTheCapital;

        return $this;
    }

    /**
     * @param Country $country
     * @return Destination
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param array $description
     * @return Destination
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $tips
     * @return Destination
     */
    public function setTips($tips)
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * @return array
     */
    public function getTips()
    {
        return $this->tips;
    }

    /**
     * @return boolean
     */
    public function isPartial()
    {
        return $this->isPartial;
    }

    /**
     * @param boolean $isPartial
     * @return $this
     */
    public function setIsPartial($isPartial)
    {
        if (false !== $this->isPartial && !$isPartial) {
            $this->completedAt = new \DateTime('now');
        }

        $this->isPartial = $isPartial;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }
}
