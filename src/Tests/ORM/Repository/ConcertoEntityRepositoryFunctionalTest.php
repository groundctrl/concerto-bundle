<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\ORM\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ConcertoEntityRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

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

    public function testFindEntityById()
    {
        $this->loadFixtures([ 'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadHostnameData' ]);

        /** @var \Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\HostnameSoloist $site */
        $site = $this->em
            ->getRepository('CtrlConcertoBundle:HostnameSoloist')
            ->findOneByDomain('concerto.dev');
        ;

        $this->assertEquals('concerto.dev', $site->getDomain());
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
