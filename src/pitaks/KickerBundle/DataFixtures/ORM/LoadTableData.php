<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.28
 * Time: 18:58
 */

namespace pitaks\KickerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use pitaks\KickerBundle\Entity\Tables;

class LoadTableData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $table = new Tables();
        $table->setName('Kaunas NFQ');
        $table->setApiUrl('http://wonderwall.ox.nfq.lt/kickertable/api/v1/events?');
        $table->setUserName('nfq');
        $table->setPassword('labas');
        $table->setIsFree(true);
        $table->setAddress('Kaunas Brastos g. 15');
        $manager->persist($table);
        $manager->flush();
    }
}