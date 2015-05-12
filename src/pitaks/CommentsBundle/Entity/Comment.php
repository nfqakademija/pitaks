<?php

namespace pitaks\CommentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use pitaks\UserBundle\Entity\User;

/**
 * @ORM\Table(name="Comment", options={"collate"="utf8mb4_lithuanian_ci", "charset"="utf8mb4", "engine"="MyISAM"})
 * @ORM\Entity(repositoryClass="pitaks\CommentsBundle\Entity\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="\pitaks\UserBundle\Entity\User", inversedBy="comments")
     */
    protected $userId;

    /**
     * @ORM\ManyToMany(targetEntity="pitaks\KickerBundle\Entity\Tables", mappedBy="comments")
     **/
    protected $tableId;

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
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * @param mixed $tableId
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId(User $userId)
    {
        $this->userId = $userId;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tableId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tableId
     *
     * @param \pitaks\KickerBundle\Entity\Tables $tableId
     * @return Comment
     */
    public function addTableId(\pitaks\KickerBundle\Entity\Tables $tableId)
    {
        $this->tableId[] = $tableId;

        return $this;
    }

    /**
     * Remove tableId
     *
     * @param \pitaks\KickerBundle\Entity\Tables $tableId
     */
    public function removeTableId(\pitaks\KickerBundle\Entity\Tables $tableId)
    {
        $this->tableId->removeElement($tableId);
    }
}
