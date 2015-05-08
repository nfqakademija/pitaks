<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.08
 * Time: 13:28
 */
namespace pitaks\RSSFeedBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RSSFeedGetterCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('pitaks:updateRSS')
            ->setDescription('rss getter');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('rss_feed_getter_service')->updateRssNews();
        $output->writeln("rss feed was updated");
    }

}
