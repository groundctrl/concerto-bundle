<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Event;

use Ctrl\Bundle\ConcertoBundle\Event\SoloEvent;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

class SoloEventTest extends ConcertoTestCase
{
    protected $soloist;
    protected $respEvt;

    function setUp()
    {
        $this->soloist = $this->mock('Ctrl\Bundle\ConcertoBundle\Model\Soloist', null);
        $this->respEvt = $this->mock('Symfony\Component\HttpKernel\Event\GetResponseEvent', null);
    }


    function testConstructableWithBothArgs()
    {
        $this->assertInstanceOf('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent',
            new SoloEvent($this->soloist, $this->respEvt));
    }

    function testConstructableWithSoloistOnly()
    {
        $this->assertInstanceOf('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent',
            new SoloEvent($this->soloist, null));
    }

    function testConstructableWithGetResponseEventOnly()
    {
        $this->assertInstanceOf('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent',
            new SoloEvent(null, $this->respEvt));
    }

    function testConstructableWithBothNull()
    {
        $this->assertInstanceOf('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent',
            new SoloEvent(null, null));
    }
}