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
        $GRE = $this->getGRE();

        $this->dispatcher->dispatch(KernelEvents::REQUEST, $GRE);

        $newEntity = new ConcertoTestAwareEntity();
        $newEntity->setName('Dave');

        $this->em->persist($newEntity);
        $this->em->flush();

        $this->assertSame($this->em->getSoloist()->getId(),
            $newEntity->getSoloist()->getId());
    }

    function testItDoesNotAlterSoloistUnawareEntities()
    {
        $this->loadFixtures([   'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadSoloistAwareEntityData' ]);
        $GRE = $this->getGRE();

        $this->dispatcher->dispatch(KernelEvents::REQUEST, $GRE);

        $prePreFlushEntity = new ConcertoTestUnawareEntity();
        $prePreFlushEntity->setName('Ethel');

        $this->em->persist($prePreFlushEntity);
        $this->em->flush();

        $postPreFlushEntity = $this->em->getRepository('CtrlConcertoBundle:ConcertoTestUnawareEntity')
            ->findOneByName('Ethel');

        $this->assertSame($prePreFlushEntity,
                         $postPreFlushEntity);
    }
}
