<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Solo;


use Ctrl\Bundle\ConcertoBundle\Solo\RepositorySolo;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

class RepositorySoloTest extends ConcertoTestCase
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $repositoryMock;

    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $requestMock;

    /** @var \Ctrl\Bundle\ConcertoBundle\Model\Soloist */
    protected $soloistStub;

    /** @var \Symfony\Component\HttpFoundation\ServerBag */
    protected $serverMock;

    function setUp()
    {
        $this->soloistStub = $this->mock('Ctrl\Bundle\ConcertoBundle\Model\Soloist', null);

        $this->repositoryMock = $this->mock(
            'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoFakeEntityCustomRepository')
        ;

        $this->serverMock = $this->mock('Symfony\Component\HttpFoundation\ServerBag')
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
        $rMethod = 'repoSoloMethod';

        $this->repositoryMock = $this->repositoryMock
            ->repoSoloMethod(['www.mysite.com'], $this->soloistStub)
            ->new()
        ;

        $sut = new RepositorySolo($this->repositoryMock, $rMethod);

        $this->assertSame($this->soloistStub, $sut->getSoloist($this->requestMock));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Could not find a soloist using solo:
     */
    function testGetSoloistErrorsOnFailedFind()
    {
        $rMethod = 'repoSoloMethod';

        $this->repositoryMock = $this->repositoryMock
            ->repoSoloMethod(['www.mysite.com'], null)
            ->new()
        ;

        $sut = new RepositorySolo($this->repositoryMock, $rMethod);
        $sut->getSoloist($this->requestMock);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage The entity found does not extend Soloist:
     */
    function testGetSoloistErrorsOnBadFind()
    {
        $rMethod = 'repoSoloMethod';

        $nonSoloist = $this->mock('Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoRegularFakeEntity', null);

        $this->repositoryMock = $this->repositoryMock
            ->repoSoloMethod(['www.mysite.com'], $nonSoloist)
            ->new()
        ;

        $sut = new RepositorySolo($this->repositoryMock, $rMethod);
        $sut->getSoloist($this->requestMock);
    }
} 