<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.22
 * Time: 23:25
 */
namespace pitaks\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var integer
     *
     * @ORM\Column(name="cardId", type="integer",nullable=true)
     */
    protected $cardId;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=60,nullable=true)
     *
     */
    protected $name;
    
    /**
     * @var string
     * @ORM\Column(name="lastName", type="string", length=60,nullable=true)
     *
     */
    protected $lastName;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\KickerBundle\Entity\TableRate", mappedBy="username")
     */
    protected $tablesRating;

    public function __construct()
    {
        parent::__construct();
        $this->tablesRating= new ArrayCollection();
    }



    /**
     * @return mixed
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * @param mixed $cardId
     */
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;
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
     * @return User
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
     * Set lastName
     *
     * @param string $lastName
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
     * Add tablesRating
     *
     * @param \pitaks\KickerBundle\Entity\TableRate $tablesRating
     * @return User
     */
    public function addTablesRating(\pitaks\KickerBundle\Entity\TableRate $tablesRating)
    {
        $this->tablesRating[] = $tablesRating;

        return $this;
    }

    /**
     * Remove tablesRating
     *
     * @param \pitaks\KickerBundle\Entity\TableRate $tablesRating
     */
    public function removeTablesRating(\pitaks\KickerBundle\Entity\TableRate $tablesRating)
    {
        $this->tablesRating->removeElement($tablesRating);
    }

    /**
     * Get tablesRating
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTablesRating()
    {
        return $this->tablesRating;
    }
}