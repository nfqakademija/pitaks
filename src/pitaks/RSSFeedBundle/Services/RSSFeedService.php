<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.08
 * Time: 01:06
 */
namespace pitaks\RSSFeedBundle\Services;
use Doctrine\ORM\EntityManager;
use pitaks\RSSFeedBundle\Entity\FeedEntry;
use pitaks\RSSFeedBundle\Entity\FeedProvider;
use Symfony\Component\DependencyInjection\ContainerAware;

class RSSFeedService extends ContainerAware{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    public function updateRssNews()
    {
        $providers = $this->em->getRepository('pitaksRSSFeedBundle:FeedProvider')->findAll();
        if($providers)
        {
            foreach($providers as $provider)
            {
                $this->pushProviderEntries($provider);
            }
        }
    }

    /**
     * @param FeedProvider $provider
     */
    public function pushProviderEntries($provider)
    {
        $reader = $this->container->get('debril.reader');
        $feed = $reader->getFeedContent($provider->getRssUrl());
        $items = $feed->getItems();
        foreach($items as $item)
        {
            if($item->getUpdated() > $provider->getLastUpdated())
            $this->pushFeedItem($item,$provider);
        }
        $provider->setLastUpdated(new \DateTime());
        $this->em->flush();
    }

    /**
     * @param $item
     * @param FeedProvider $provider
     */
    public function pushFeedItem($item, $provider)
    {
        $feed_entry =new FeedEntry();
        $feed_entry->setGuid($item->getPublicId());
        $feed_entry->setDate($item->getUpdated());
        $feed_entry->setDescription($item->getDescription());
        $feed_entry->setTitle($item->getTitle());
        $feed_entry->setLink($item->getLink());
        $feed_entry->setProviderId($provider);
        $this->em->persist($feed_entry);
        $this->em->flush();
    }

}