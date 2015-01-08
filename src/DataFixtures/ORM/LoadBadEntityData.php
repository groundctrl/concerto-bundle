<?php

namespace Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM;


use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestBadAwareEntity;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestSoloist;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBadEntityData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$soloist = new ConcertoTestSoloist();
		$soloist->setDomain('alice.com');
		$manager->persist($soloist);

		$badEntity = new ConcertoTestBadAwareEntity();
		$badEntity->setName('Bob');
		$badEntity->setSoloist($soloist);

		$manager->persist($badEntity);

		$manager->flush();
	}
}
