<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\ORM\Repository;


use Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

class ConcertoEntityRepositoryTest extends ConcertoTestCase
{
    function setUp()
    {
        $this->soloistStub = $this->mock('Ctrl\Bundle\ConcertoBundle\Model\Soloist', null);
        $this->cm = $this->mock('Doctrine\ORM\Mapping\ClassMetadata', null);
        $this->em = $this->getMockBuilder('Ctrl\Bundle\ConcertoBundle\ORM\Conductor')
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableProxyingToOriginalMethods()
            ->disableAutoload()
            ->setMethods(['find'])
            ->getMock();
    }

    function testSuccessfulFindsProduceASoloistAwareFacade()
    {
        $this->em->expects($this->once())
            ->method('find')
            ->with($this->anything(), 'bob', $this->anything(), $this->anything())
            ->willReturn($this->soloistStub);

        $sut = new ConcertoEntityRepository($this->em, $this->cm);
        $bob = $sut->find('bob');
        $this->assertInstanceOf('Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareFacade', $bob);
    }

    function testUnsuccessfulFindsReturnNull()
    {
        $this->em->expects($this->once())
            ->method('find')
            ->with($this->anything(), 'bob', $this->anything(), $this->anything())
            ->willReturn(null);

        $sut = new ConcertoEntityRepository($this->em, $this->cm);
        $bob = $sut->find('bob');
        $this->assertNull($bob);
    }
} 