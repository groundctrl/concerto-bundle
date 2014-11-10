<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\EventListener;

use Ctrl\Bundle\ConcertoBundle\EventListener\FindSoloistListener;
use Ctrl\Bundle\ConcertoBundle\SoloEvents;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Prophecy\Argument;

class FindSoloistListenerTest extends ConcertoTestCase
{
    /** @var ContainerInterface */
    public $cont;

    /** @var EventDispatcherInterface */
    public $disp;

    /** @var \Ctrl\Bundle\ConcertoBundle\Solo\SoloInterface */
    public $solo;

    /** @var \Ctrl\Bundle\ConcertoBundle\Model\Soloist */
    public $soloistMock;

    /** @var \Symfony\Component\HttpKernel\Event\GetResponseEvent */
    public $rspEvtMock;

    /** @var \Ctrl\Bundle\ConcertoBundle\Event\SoloEvent */
    public $sloEvtMock;

    function setUp()
    {
        $this->cont = $this->mock('Symfony\Component\DependencyInjection\Container');
        $this->disp = $this->mock('Symfony\Component\EventDispatcher\EventDispatcher');
        $this->solo = $this->mock('Ctrl\Bundle\ConcertoBundle\Solo\HostnameSolo');
        $this->sloEvtMock = $this->mock('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent');

        $this->soloistMock = $this->mock('Ctrl\Bundle\ConcertoBundle\Model\Soloist')->getId(13)->new();

        $this->requestStub = $this->mock('Symfony\Component\HttpFoundation\Request', null);

        $this->rspEvtMock = $this->mock('Symfony\Component\HttpKernel\Event\GetResponseEvent');

    }

    function testOnEarlyKernelRequestWorksWhenSoloHasSoloist()
    {
        $rspEvtMock = $this->rspEvtMock
            ->getRequest($this->requestStub, $this->once())
            ->new()
        ;

        $solo = $this->solo
            ->getSoloist([$this->requestStub], $this->soloistMock, $this->once())
            ->new()
        ;

        $sloEvtMock = $this->sloEvtMock
            ->getSoloist([], null, $this->once())
            ->new()
        ;

        $dispatcher = $this->disp
            ->dispatch(
                [
                    SoloEvents::SOLOIST_FOUND,
                    $this->argTypeCallback('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent')
                ],
                $this->sloEvtMock,
                $this->exactly(1)
            )
            ->new()
        ;

        $container = $this->cont
            ->set(['concerto.soloist', $this->soloistMock], null, $this->once())
            ->new()
        ;

        $sut = new FindSoloistListener($container, $dispatcher, $solo);
        $sut->onEarlyKernelRequest($rspEvtMock);

        $this->assertSame($sut->getSoloist(), $this->soloistMock);
    }

    function testOnEarlyKernelRequestWorksWhenSoloEventHasSoloist()
    {
        $rspEvtMock = $this->rspEvtMock
            ->getRequest($this->requestStub, $this->once())
            ->new()
        ;

        $solo = $this->solo
            ->getSoloist([$this->requestStub], null, $this->once())
            ->new()
        ;

        $sloEvtMock = $this->sloEvtMock
            ->getSoloist($this->soloistMock, $this->once())
            ->new()
        ;

        $dispatcher = $this->mockTwoDispatches(
            [SoloEvents::SOLOIST_NOT_FOUND, $sloEvtMock],
            [SoloEvents::SOLOIST_FOUND, null]
        );

        $container = $this->cont
            ->set(['concerto.soloist', $this->soloistMock], null, $this->once())
            ->new()
        ;

        $sut = new FindSoloistListener($container, $dispatcher, $solo);
        $sut->onEarlyKernelRequest($rspEvtMock);

        $this->assertSame($sut->getSoloist(), $this->soloistMock);
    }

    function testOnEarlyKernelRequestWorksWhenNoSoloistIsAvailable()
    {
        $rspEvtMock = $this->rspEvtMock
            ->getRequest($this->requestStub, $this->once())
            ->new()
        ;

        $solo = $this->solo
            ->getSoloist([$this->requestStub], null, $this->once())
            ->new()
        ;

        $sloEvtMock = $this->sloEvtMock
            ->getSoloist([], null, $this->once())
            ->new()
        ;

        $dispatcher = $this->mockTwoDispatches(
            [SoloEvents::SOLOIST_NOT_FOUND, $sloEvtMock],
            [SoloEvents::SOLOIST_NOT_FOUND, null]
        );

        $container = $this->cont
            ->set(['concerto.soloist', $this->soloistMock], null, $this->never())
            ->new()
        ;

        $sut = new FindSoloistListener($container, $dispatcher, $solo);
        $sut->onEarlyKernelRequest($rspEvtMock);

        $this->assertSame($sut->getSoloist(), null);
    }

    function testItRespondsToOnEarlyKernelRequestEvents()
    {
        $rspEvtMock = $this->rspEvtMock
            ->getRequest($this->requestStub, $this->never())
            ->new()
        ;

        $container = $this->mock('Symfony\Component\DependencyInjection\Container', null);

        $solo = $this->mock('Ctrl\Bundle\ConcertoBundle\Solo\HostnameSolo')->new();


        $dispatcher = new EventDispatcher();

        $sut = $this->getMockBuilder('Ctrl\Bundle\ConcertoBundle\EventListener\FindSoloistListener')
            ->setMethods(['onEarlyKernelRequest', 'getSoloist', 'setSoloist'])
            ->setConstructorArgs([$container, $dispatcher, $solo])
            ->getMock();

        $sut->expects($this->once())
            ->method('onEarlyKernelRequest')
            ->with($rspEvtMock);

        $dispatcher->addListener('kernel.request', [$sut, 'onEarlyKernelRequest']);

        $dispatcher->dispatch('kernel.request', $rspEvtMock);
    }

    function mockTwoDispatches($call1, $call2)
    {
        list($arg1, $ret1) = $call1;
        list($arg2, $ret2) = $call2;

        return $this->disp
            ->dispatch(
                [
                    $arg1,
                    $this->argTypeCallback('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent')
                ],
                $ret1,
                $this->at(0)
            )->dispatch(
                [
                    $arg2,
                    $this->argTypeCallback('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent')
                ],
                $ret2,
                $this->at(1)
            )->new();
    }
}