<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

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

        /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
        $this->dispatcher = static::$kernel->getContainer()
            ->get('event_dispatcher');


    }

    public function someDomains()
    {
        // LISTOF [ID, DOMAIN_NAME] COMING FROM LOADHOSTNAMEDATA
        return [ [ 1, 'alice.com' ],
                 [ 2, 'bob.com'   ],
                 [ 3, 'carl.com'  ] ];
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
