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
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function someDomains()
    {
        // LISTOF [ID, DOMAIN_NAME] COMING FROM LOADHOSTNAMEDATA
        return [ [ 1, 'concerto.dev' ],
                 [ 2, 'google.com'   ],
                 [ 3, 'symfony.com'  ] ];
    }

    public function someRegularEntities()
    {
        //LISTOF [ID, ENTITY_NAME] COMING FROM LOADENTITYDATA
        return [ [ 1, 'Alice' ],
                 [ 2, 'Bob'   ],
                 [ 3, 'Carl'  ] ];
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
}