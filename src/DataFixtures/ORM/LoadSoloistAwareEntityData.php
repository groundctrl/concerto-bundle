<?php

namespace Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM;

use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestSoloistLinkingEntity;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestSoloist;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSoloistAwareEntityData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $soloist = new ConcertoTestSoloist();
        $soloist->setDomain('alice.com');
        $manager->persist($soloist);

        $entity = new ConcertoTestAwareEntity();
        $entity->setName("Alice");
        $entity->setSoloist($soloist);
        $manager->persist($entity);

        $link = new ConcertoTestSoloistLinkingEntity();
        $link->setDomain('alice.com');
        $link->setSoloist($soloist);
        $manager->persist($link);


        $soloist = new ConcertoTestSoloist();
        $soloist->setDomain('bob.com');
        $manager->persist($soloist);

        $entity = new ConcertoTestAwareEntity();
        $entity->setName("Bob");
        $entity->setSoloist($soloist);
        $manager->persist($entity);

        $link = new ConcertoTestSoloistLinkingEntity();
        $link->setDomain('bob.com');
        $link->setSoloist($soloist);
        $manager->persist($link);


        $soloist = new ConcertoTestSoloist();
        $soloist->setDomain('carl.com');
        $manager->persist($soloist);

        $entity = new ConcertoTestAwareEntity();
        $entity->setName("Carl");
        $entity->setSoloist($soloist);
        $manager->persist($entity);

        $link = new ConcertoTestSoloistLinkingEntity();
        $link->setDomain('carl.com');
        $link->setSoloist($soloist);
        $manager->persist($link);


        $manager->flush();
    }
}
