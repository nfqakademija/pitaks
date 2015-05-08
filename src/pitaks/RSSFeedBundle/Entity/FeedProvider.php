<?php

namespace pitaks\RSSFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedProvider
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\RSSFeedBundle\Entity\FeedProviderRepository")
 */
class FeedProvider
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
     * @var string
     *
     * @ORM\Column(name="rss_url", type="string", length=255)
     */
    private $rssUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdated", type="datetime")
     */
    private $lastUpdated;


    /**
     * @ORM\OneToMany(targetEntity="FeedEntry", mappedBy="providerId")
     */
    private $feed_entries;



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
     * @return FeedProvider
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
     * Set rssUrl
     *
     * @param string $rssUrl
     * @return FeedProvider
     */
    public function setRssUrl($rssUrl)
    {
        $this->rssUrl = $rssUrl;

        return $this;
    }

    /**
     * Get rssUrl
     *
     * @return string 
     */
    public function getRssUrl()
    {
        return $this->rssUrl;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastUpdated
     * @return FeedProvider
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \DateTime 
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->feed_entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add feed_entries
     *
     * @param \pitaks\RSSFeedBundle\Entity\FeedEntry $feedEntries
     * @return FeedProvider
     */
    public function addFeedEntry(\pitaks\RSSFeedBundle\Entity\FeedEntry $feedEntries)
    {
        $this->feed_entries[] = $feedEntries;

        return $this;
    }

    /**
     * Remove feed_entries
     *
     * @param \pitaks\RSSFeedBundle\Entity\FeedEntry $feedEntries
     */
    public function removeFeedEntry(\pitaks\RSSFeedBundle\Entity\FeedEntry $feedEntries)
    {
        $this->feed_entries->removeElement($feedEntries);
    }

    /**
     * Get feed_entries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeedEntries()
    {
        return $this->feed_entries;
    }
}
