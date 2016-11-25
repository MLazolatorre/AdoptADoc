<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Image", cascade={"persist","remove"})
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\column(name="lastName", type="string")
     *
     */
     private $lastName;

    /**
     * @var string
     *
     * @ORM\column(name="speciality", type="string")
     **/
    private $speciality;

    /**
     * @var string
     *
     * @ORM\column(name="location", type="string")
     */
    private $location;

    /**
     * @param Image $image
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return \UserBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set speciality
     *
     * @param string $speciality
     *
     * @return User
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return string
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return User
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }
}
