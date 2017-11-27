<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_travel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"user", "user-read"}},
 *     "denormalization_context"={"groups"={"user", "user-write"}}
 * })
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|Voyage[]
     * @ORM\OneToMany(targetEntity="Voyage", mappedBy="user", cascade={"remove"})
     * @Groups({"user-read"})
     */
    private $voyages;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Groups({"user-read", "user-write"})
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=125)
     * @Groups({"user-read", "user-write"})
     */
    protected $username;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @Groups({"user-write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=125)
     * @var string
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user-read", "user-write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=125)
     */
    protected $fullname;

    public function __construct()
    {
        parent::__construct();
        $this->voyages = new ArrayCollection();
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isUser(UserInterface $user = null)
    {
        return $user instanceof self && $user->id === $this->id;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Voyage $voyage
     * @return User
     */
    public function addVoyage(Voyage $voyage)
    {
        $this->voyages[] = $voyage;

        return $this;
    }

    /**
     * @param Voyage $voyage
     */
    public function removeVoyage(Voyage $voyage)
    {
        $this->voyages->removeElement($voyage);
    }

    /**
     * @return ArrayCollection|Voyage[]
     */
    public function getVoyages()
    {
        return $this->voyages;
    }
}
