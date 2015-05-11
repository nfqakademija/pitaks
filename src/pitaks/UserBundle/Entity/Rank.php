<?php

namespace pitaks\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rank
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\UserBundle\Entity\RankRepository")
 */
class Rank
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="win", type="integer")
     */
    private $win;

    /**
     * @var integer
     *
     * @ORM\Column(name="scored", type="integer")
     */
    private $scored;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="rank")
     */
    private $users;

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
     * @return Rank
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
     * Set win
     *
     * @param integer $win
     * @return Rank
     */
    public function setWin($win)
    {
        $this->win = $win;

        return $this;
    }

    /**
     * Get win
     *
     * @return integer 
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * Set scored
     *
     * @param integer $scored
     * @return Rank
     */
    public function setScored($scored)
    {
        $this->scored = $scored;

        return $this;
    }

    /**
     * Get scored
     *
     * @return integer 
     */
    public function getScored()
    {
        return $this->scored;
    }

    /**
     * Add users
     *
     * @param \pitaks\UserBundle\Entity\User $users
     * @return Rank
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
}
