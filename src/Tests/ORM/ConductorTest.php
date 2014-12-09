<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\ORM;

use Ctrl\Bundle\ConcertoBundle\ORM\Conductor;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Mapping as ORM;

class ConductorTest extends ConcertoTestCase
{
    /**
     * @expectedException \Doctrine\ORM\ORMException
     * @expectedExceptionMessage It's a requirement to specify a Metadata Driver
     */
    function testCreationFailsOnBadMappingDriver()
    {
        $conf = $this->mock('Doctrine\ORM\Configuration')
            ->getMetadataDriverImpl(null)
            ->new();

        $sut = Conductor::create(null, $conf);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid argument:
     */
    function testCreationFailsOnBadConnection()
    {
        $conf = $this->mock('Doctrine\ORM\Configuration')
            ->getMetadataDriverImpl(true)
            ->new();

        $sut = Conductor::create(null, $conf);
    }

    /**
     * @expectedException \Doctrine\ORM\ORMException
     * @expectedExceptionMessage Cannot use different EventManager instances
     */
    function testCreationFailsOnMismatchedEntityManagers()
    {
        $conn = $this->mock('Doctrine\DBAL\Connection')
            ->getEventManager( $this->mock('Doctrine\Common\EventManager', null) )
            ->new();
        $conf = $this->mock('Doctrine\ORM\Configuration')
            ->getMetadataDriverImpl(true)
            ->new();

        $sut = Conductor::create($conn, $conf, new EventManager());
    }

    function testItCanBeCreated()
    {
        $this->assertInstanceOf('Ctrl\Bundle\ConcertoBundle\ORM\Conductor', $this->createTestConductor());
    }
}