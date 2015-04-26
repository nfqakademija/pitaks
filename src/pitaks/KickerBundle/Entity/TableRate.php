<?php

namespace pitaks\KickerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;
/**
 * TableRate
 *
 * @ORM\Table(name="tableRate", uniqueConstraints={@UniqueConstraint(name="rating_unique", columns={"tableId", "username"})})
 *
 * @ORM\Entity
 */
class TableRate
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
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;


    /**
     * @ORM\ManyToOne(targetEntity="Tables", inversedBy="ratings")
     * @ORM\JoinColumn(name="tableId", referencedColumnName="id")
     */
    private $tableId;


    /**
     * @ORM\ManyToOne(targetEntity="\pitaks\UserBundle\Entity\User", inversedBy="tablesRating")
     * @ORM\JoinColumn(name="username", referencedColumnName="id")
     */
    private $username;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   

    /**
     * Set rating
     *
     * @param integer $rating
     * @return TableRate
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set tableId
     *
     * @param \pitaks\KickerBundle\Entity\Tables $tableId
     * @return TableRate
     */
    public function setTableId(\pitaks\KickerBundle\Entity\Tables $tableId = null)
    {
        $this->tableId = $tableId;

        return $this;
    }

    /**
     * Get tableId
     *
     * @return \pitaks\KickerBundle\Entity\Tables 
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Set username
     *
     * @param \pitaks\UserBundle\Entity\User $username
     * @return TableRate
     */
    public function setUsername(\pitaks\UserBundle\Entity\User $username = null)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return \pitaks\UserBundle\Entity\User
     */
    public function getUsername()
    {
        return $this->username;
    }
}
