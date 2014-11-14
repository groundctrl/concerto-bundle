<?php

namespace Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM;

use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\HostnameSoloist;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadHostnameData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $site = new HostnameSoloist();
        $site->setDomain('concerto.dev');
        $manager->persist($site);

        $site = new HostnameSoloist();
        $site->setDomain('google.com');
        $manager->persist($site);

        $site = new HostnameSoloist();
        $site->setDomain('symfony.com');
        $manager->persist($site);

        $manager->flush();
    }
}
