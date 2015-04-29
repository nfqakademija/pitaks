<?php

namespace pitaks\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\UserBundle\Entity\TeamRepository")
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
     * @var integer
     *
     * @ORM\Column(name="user1", type="integer")
     */
    private $user1;

    /**
     * @var integer
     *
     * @ORM\Column(name="user2", type="integer")
     */
    private $user2;


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
     * Set user1
     *
     * @param integer $user1
     * @return Team
     */
    public function setUser1($user1)
    {
        $this->user1 = $user1;

        return $this;
    }

    /**
     * Get user1
     *
     * @return integer 
     */
    public function getUser1()
    {
        return $this->user1;
    }

    /**
     * Set user2
     *
     * @param integer $user2
     * @return Team
     */
    public function setUser2($user2)
    {
        $this->user2 = $user2;

        return $this;
    }

    /**
     * Get user2
     *
     * @return integer 
     */
    public function getUser2()
    {
        return $this->user2;
    }
}
