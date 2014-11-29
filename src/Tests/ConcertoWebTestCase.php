<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests;

use Ctrl\Bundle\ConcertoBundle\EventSubscriber\ClaimEntitySubscriber;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ConcertoWebTestCase extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public $dispatcher;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $this->em->getEventManager()->addEventSubscriber(new ClaimEntitySubscriber());
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
        $this->dispatcher = static::$kernel->getContainer()
            ->get('event_dispatcher');


    }

    function getGRE()
    {
        $request = Request::create('http://alice.com');
        $GRE = new GetResponseEvent(static::$kernel, $request, HttpKernelInterface::MASTER_REQUEST);
        return $GRE;
    }

    public function someDomains()
    {
        // LISTOF [ID, DOMAIN_NAME] COMING FROM LoadSoloistUnawareEntityData
        return [ [ 1, 'alice.com' ],
                 [ 2,   'bob.com' ],
                 [ 3,  'carl.com' ] ];
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        $this->em->close();
        parent::tearDown();
    }
}
