<?php

namespace pitaks\TeamBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use pitaks\UserBundle\Entity\User;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\TeamBundle\Entity\TeamRepository")
 */
class Team
{
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
     * 
     * @ORM\Column(name="name", type="string", length=60)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registeredDate", type="datetime")
     */
    private $registeredDate;

    /**
     * @ORM\ManyToMany(targetEntity="\pitaks\UserBundle\Entity\User", mappedBy="teams")
     **/
    private $users;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmedDate", type="datetime",nullable=true)
     */
    private $confirmedDate;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="pitaks\UserBundle\Entity\User", inversedBy="createdTeams")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    function __construct()
    {
        $this->users = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set registeredDate
     *
     * @param \DateTime $registeredDate
     * @return Team
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;

        return $this;
    }

    /**
     * Get registeredDate
     *
     * @return \DateTime 
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }


    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return Team
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Add users
     *
     * @param \pitaks\UserBundle\Entity\User $users
     * @return Team
     */
    public function addUser(\pitaks\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \pitaks\UserBundle\Entity\User $users
     */
    public function removeUser(\pitaks\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set confirmedDate
     *
     * @param \DateTime $confirmedDate
     * @return Team
     */
    public function setConfirmedDate($confirmedDate)
    {
        $this->confirmedDate = $confirmedDate;

        return $this;
    }

    /**
     * Get confirmedDate
     *
     * @return \DateTime 
     */
    public function getConfirmedDate()
    {
        return $this->confirmedDate;
    }

    /**
     * Set author
     *
     * @param \pitaks\UserBundle\Entity\User $authorId
     * @return Team
     */
    public function setAuthor(\pitaks\UserBundle\Entity\User $authorId = null)
    {
        $this->author = $authorId;

        return $this;
    }

    /**
     * Get author
     *
     * @return \pitaks\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
