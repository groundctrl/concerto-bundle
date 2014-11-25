<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\ORM\Filter;

use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class SoloistFilterFunctionalTest extends ConcertoWebTestCase
{

    private $awareCount;

    function setUp()
    {
        parent::setUp();
        $solo = get_class($this->getContainer()->get('concerto.solo'));
        $solo = strtolower(preg_replace("/^(.*\\\\)/i", "", $solo));

        if( array_search($solo, ['hostnamesolo', 'repositorysolo']) === false ) {
            $this->markTestSkipped('This test is made to work with the default solos, hostname and repository.');
        }
    }
    function getGRE()
    {
        $request = Request::create('http://alice.com');
        $GRE = new GetResponseEvent(static::$kernel, $request, HttpKernelInterface::MASTER_REQUEST);
        return $GRE;
    }

    function testFilterDoesNotAddCriteriaToQueryIfEntityNotSoloistAware()
    {
        $this->loadFixtures([ 'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadSoloistUnawareEntityData' ]);
        $GRE = $this->getGRE();
        $this->dispatcher->dispatch(KernelEvents::REQUEST, $GRE);
        $this->assertCount(3, $this->em->getRepository('CtrlConcertoBundle:ConcertoTestUnawareEntity')->findAll());
    }

    function testFilterAddsCriteriaToQueryIfEntityIsSoloistAware()
    {
        $this->loadFixtures([ 'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadSoloistAwareEntityData' ]);
        $GRE = $this->getGRE();
        $this->dispatcher->dispatch(KernelEvents::REQUEST, $GRE);
        $this->assertCount(1, $this->em->getRepository('CtrlConcertoBundle:ConcertoTestAwareEntity')->findAll() );
    }
}
