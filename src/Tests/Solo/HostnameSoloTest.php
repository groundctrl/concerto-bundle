<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Solo;

use Ctrl\Bundle\ConcertoBundle\Solo\HostnameSolo;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

class HostnameSoloTest extends ConcertoTestCase
{
    /** @var \Ctrl\Bundle\ConcertoBundle\Model\Soloist */
    public $soloistStub;

    /** @var \Doctrine\ORM\EntityRepository */
    public $repoMock;

    /** @var \Symfony\Component\HttpFoundation\Request */
    public $requestMock;

    /** @var string */
    public $hostName = 'www.mysite.com';

    /** @var string */
    public $field = 'soloist_field';

    function setUp()
    {
        $this->soloistStub = $this->mock('Ctrl\Bundle\ConcertoBundle\Model\Soloist', null);

        $this->repoMock    = $this->mock('Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository');
        $this->serverMock  = $this->mock('Symfony\Component\HttpFoundation\ServerBag')
            ->get(['SERVER_NAME'], 'www.mysite.com', $this->once())
            ->new()
        ;

        $this->requestMock = $this->mock('Symfony\Component\HttpFoundation\Request',
            [
                'server' => $this->serverMock
            ])
        ;
    }

    function testItCanGetTheSoloist()
    {
        $this->repoMock = $this->repoMock
            ->findOneBy([ [$this->field => $this->hostName] ], $this->soloistStub, $this->once())
            ->new()
        ;

        $sut = new HostnameSolo($this->repoMock, $this->field);
        $this->assertSame($this->soloistStub, $sut->getSoloist($this->requestMock));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Could not find a soloist using solo:
     */
    function testGetSoloistErrorsOnFailedFind()
    {
        $this->repoMock = $this->repoMock
            ->findOneBy([ [$this->field => $this->hostName] ], null, $this->once())
            ->new()
        ;

        $sut = new HostnameSolo($this->repoMock, $this->field);
        $sut->getSoloist($this->requestMock);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage The entity found does not extend Soloist:
     */
    function testGetSoloistErrorsOnBadFind()
    {
        $nonSoloist = $this->mock('Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoRegularFakeEntity', null);

        $this->repoMock = $this->repoMock
            ->findOneBy([ [$this->field => $this->hostName] ], $nonSoloist, $this->once())
            ->new()
        ;

        $sut = new HostnameSolo($this->repoMock, $this->field);
        $sut->getSoloist($this->requestMock);
    }
} 