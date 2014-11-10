<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Traits;

use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareFacade;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTraitFakeEntity;
use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoConcertoFakeEntity;
use Doctrine\ORM\UnitOfWork;

class ConcertoMemberTraitTest extends ConcertoTestCase
{
    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Caller of _onPropertyChanged
     */
    function testListenersErrorProperlyWhenOnPropertyChangedCalledFromEntity()
    {
        $sut = new ConcertoTraitFakeEntity();
        $sut->_onPropertyChanged(0,0,0);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage You cannot call _onPropertyChanged directly
     */
    function testListenersErrorProperlyWhenOnPropertyChangedCalledFromFacade()
    {
        $sut = new ConcertoTraitFakeEntity();
        $em = $this->createTestConductor();
        $meta = $em->getClassMetadata(get_class($sut));

        $wrap = new SoloistAwareFacade($sut, $meta);
        $wrap->_onPropertyChanged(0,0,0);
    }

    function testListenersWorkWhenPropertyChanges()
    {
        $sut = new ConcertoTraitFakeEntity();
        $em = $this->createTestConductor();
        $meta = $em->getClassMetadata(get_class($sut));

        $wrap = new SoloistAwareFacade($sut, $meta);

        $listener = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
            ->setConstructorArgs([$em])
            ->getMock();

        $listener->expects($this->once())
            ->method('propertyChanged');

        #@todo figure out how to do the above mock in xpmock, for consistency

        $sut->addPropertyChangedListener($listener);
        $wrap->setId(10);
    }
} 