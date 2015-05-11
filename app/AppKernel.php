<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new pitaks\KickerBundle\pitaksKickerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new pitaks\UserBundle\UserBundle(),
            new pitaks\TeamBundle\pitaksTeamBundle(),
            new Debril\RssAtomBundle\DebrilRssAtomBundle(),
            new pitaks\RSSFeedBundle\pitaksRSSFeedBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();

        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
