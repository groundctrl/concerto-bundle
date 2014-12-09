<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\EventSubscriber;

use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoWebTestCase;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestUnawareEntity;
use Symfony\Component\HttpKernel\KernelEvents;

class ClaimEntitySubscriberFunctionalTest extends ConcertoWebTestCase
{
    function testItCallsSetSoloistOnSoloistAwareEntitiesPreFlush()
    {
        $this->loadFixtures([   'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadSoloistAwareEntityData' ]);
        $GRE = $this->getGRE();  //simulate a request

        $this->dispatcher->dispatch(KernelEvents::REQUEST, $GRE); //dispatch it. $this->em now has a soloist set on it.

        //make a new SoloistAware entity
        $insertDave = new ConcertoTestAwareEntity();
        $insertDave->setName('Dave');

        //persist it without setting the soloist
        $this->em->persist($insertDave);
        $this->em->flush();

        //assert setSoloist was called preFlush on the entity we made
        $this->assertSame($this->em->getSoloist()->getId(),
            $insertDave->getSoloist()->getId());

        //also assert that same entity has a soloist if you get it back from the DB
        $findDave = $this->em->getRepository('CtrlConcertoBundle:ConcertoTestAwareEntity')->findOneByName('Dave');
        $this->assertSame($this->em->getSoloist()->getId(),
            $findDave->getSoloist()->getId());
    }

    function testItDoesNotAlterSoloistUnawareEntities()
    {
        $this->loadFixtures([   'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadSoloistAwareEntityData' ]);
        $GRE = $this->getGRE();  //simulate a request

        $this->dispatcher->dispatch(KernelEvents::REQUEST, $GRE); //dispatch it. $this->em now has a soloist set on it.

        //make a new entity which is not SoloistAware
        $insertEthel = new ConcertoTestUnawareEntity();
        $insertEthel->setName('Ethel');

        //persist it
        $this->em->persist($insertEthel);
        $this->em->flush();

        //get it back from the DB
        $findEthel = $this->em->getRepository('CtrlConcertoBundle:ConcertoTestUnawareEntity')
            ->findOneByName('Ethel');

        //assert that it's the same as the original, no changes made
        $this->assertSame($insertEthel,
                         $findEthel);
    }
}
