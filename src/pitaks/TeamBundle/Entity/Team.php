<?php

namespace pitaks\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     *
     * @ORM\Column(name="userId1", type="integer")
     */
    private $userId1;

    /**
     * @var integer
     *
     * @ORM\Column(name="userId2", type="integer")
     */
    private $userId2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;



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
     * Set userId1
     *
     * @param integer $userId1
     * @return Team
     */
    public function setUserId1($userId1)
    {
        $this->userId1 = $userId1;

        return $this;
    }

    /**
     * Get userId1
     *
     * @return integer 
     */
    public function getUserId1()
    {
        return $this->userId1;
    }

    /**
     * Set userId2
     *
     * @param integer $userId2
     * @return Team
     */
    public function setUserId2($userId2)
    {
        $this->userId2 = $userId2;

        return $this;
    }

    /**
     * Get userId2
     *
     * @return integer 
     */
    public function getUserId2()
    {
        return $this->userId2;
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
}
