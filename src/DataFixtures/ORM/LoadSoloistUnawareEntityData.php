<?php

namespace Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM;

use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestUnawareEntity;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestSoloist;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSoloistUnawareEntityData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $soloist = new ConcertoTestSoloist();
        $soloist->setDomain('alice.com');
        $entity = new ConcertoTestUnawareEntity();
        $entity->setName("Alice");
        #$entity->setSoloist($soloist);
        $manager->persist($soloist);
        $manager->persist($entity);

        $soloist = new ConcertoTestSoloist();
        $soloist->setDomain('bob.com');
        $entity = new ConcertoTestUnawareEntity();
        $entity->setName("Bob");
        #$entity->setSoloist($soloist);
        $manager->persist($soloist);
        $manager->persist($entity);

        $soloist = new ConcertoTestSoloist();
        $soloist->setDomain('carl.com');
        $entity = new ConcertoTestUnawareEntity();
        $entity->setName("Carl");
        #$entity->setSoloist($soloist);
        $manager->persist($soloist);
        $manager->persist($entity);

        $manager->flush();
    }
}
