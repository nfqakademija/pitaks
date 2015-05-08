<?php

namespace pitaks\RSSFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedEntry
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\RSSFeedBundle\Entity\FeedEntryRepository")
 */
class FeedEntry
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
     * @ORM\ManyToOne(targetEntity="FeedProvider", inversedBy="feed_entries")
     * @ORM\JoinColumn(name="providerId", referencedColumnName="id")
     */
    private $providerId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", length=255)
     */
    private $guid;


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
     * Set providerId
     *
     * @param integer $providerId
     * @return FeedEntry
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * Get providerId
     *
     * @return integer 
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return FeedEntry
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return FeedEntry
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return FeedEntry
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return FeedEntry
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return FeedEntry
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }
}
