<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\EventListener;

use Ctrl\Bundle\ConcertoBundle\EventListener\ConductSoloistListener;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

class ConductSoloistListenerTest extends ConcertoTestCase
{
    /** @var  \Ctrl\Bundle\ConcertoBundle\ORM\Conductor */
    public $emMock;

    /** @var  \Ctrl\Bundle\ConcertoBundle\Event\SoloEvent */
    public $soloEventMock;

    /** @var  \Ctrl\Bundle\ConcertoBundle\Model\Soloist */
    public $soloistStub;

    public $repoClass = 'Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository';

    function testItsOnSoloistFoundMethodWorks()
    {
        $this->soloistStub = $this->mock('Ctrl\Bundle\ConcertoBundle\Model\Soloist', null);

        $this->soloEventMock = $this->mock('Ctrl\Bundle\ConcertoBundle\Event\SoloEvent')
            ->getSoloist($this->soloistStub, $this->once())
            ->new()
        ;
        $this->emMock = $this->mock('Ctrl\Bundle\ConcertoBundle\ORM\Conductor')
            ->setConcertoRepositoryClassName([$this->repoClass], null, $this->never())
            ->setSoloist([$this->soloistStub], null, $this->once())
            ->new()
        ;

        $em = $this->createTestConductor();

        $sut = new ConductSoloistListener($em, $this->repoClass);
        $sut->onSoloistFound($this->soloEventMock);
    }
} 