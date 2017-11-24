<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *      attributes={
 *          "force_eager"=false,
 *          "normalization_context"={"groups"={"read-voyage", "read-destination-light", "read-country-light"}, "datetime_format" = "Y-m-d"},
 *          "denormalization_context"={"groups"={"write-voyage", "journey"}}
 *      },
 * )
 * @ORM\Table(name="voyage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoyageRepository")
 * @UniqueEntity("token")
 */
class Voyage implements JourneyInterface
{

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read-voyage", "write-voyage"})
     * @Assert\Choice({"TRAIN", "BUS", "FLY", "NONE", null})
     */
    private $transportType;

    /**
     * @var AvailableJourney
     * @ORM\ManyToOne(targetEntity="AvailableJourney")
     * @Groups({"read-voyage", "write-voyage"})
     */
    private $availableJourney;

    use JourneyTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read-voyage"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Groups({"read-voyage", "write-voyage"})
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(max = 255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="token", type="string", length=255, nullable=false, unique=true)
     * @Groups({"read-voyage"})
     */
    private $token;

    /**
     * @var string
     * @ORM\Column(name="url_minified", type="string", length=255, nullable=true)
     * @Groups({"read-voyage"})
     * @Assert\Length(max = 255)
     */
    private $urlMinified;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups({"read-voyage", "write-voyage"})
     * @Assert\NotNull()
     */
    private $showPricesInPublic = true;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"read-voyage", "write-voyage"})
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $startDate;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination")
     * @ORM\JoinColumn(name="destination_id", referencedColumnName="id", nullable=false)
     * @Groups({"read-voyage", "write-voyage"})
     * @Assert\NotNull()
     */
    private $startDestination;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="voyages")
     */
    private $user;

    /**
     * @var ArrayCollection|Stage[]
     * @ORM\OneToMany(targetEntity="Stage", mappedBy="voyage", cascade={"remove"})
     * @ApiSubresource
     * @ApiProperty(iri="/test/")
     * @Groups({"read-voyage"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $stages;


    public function __construct()
    {
        $this->stages = new ArrayCollection();
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
     * @return $this
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
     * @param $token
     * @return Voyage
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param \DateTime $startDate
     * @return Voyage
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param User $user
     * @return Voyage
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Stage $stages
     * @return Voyage
     */
    public function addStage(Stage $stages)
    {
        $this->stages[] = $stages;

        return $this;
    }

    /**
     * @param Stage $stages
     */
    public function removeStage(Stage $stages)
    {
        $this->stages->removeElement($stages);
    }

    /**
     * @return ArrayCollection|Stage[]
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param Destination $startDestination
     * @return Voyage
     */
    public function setStartDestination(Destination $startDestination = null)
    {
        $this->startDestination = $startDestination;

        return $this;
    }

    /**
     * @return Destination
     */
    public function getStartDestination()
    {
        return $this->startDestination;
    }

    /**
     * @return boolean
     */
    public function isShowPricesInPublic()
    {
        return $this->showPricesInPublic;
    }

    /**
     * @param boolean $showPricesInPublic
     * @return $this
     */
    public function setShowPricesInPublic($showPricesInPublic)
    {
        $this->showPricesInPublic = $showPricesInPublic;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlMinified()
    {
        return $this->urlMinified;
    }

    /**
     * @param string $urlMinified
     * @return $this
     */
    public function setUrlMinified($urlMinified)
    {
        $this->urlMinified = $urlMinified;

        return $this;
    }
}
