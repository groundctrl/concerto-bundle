<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\ORM\Repository;

use Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepositoryFactory;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;
use Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestUnawareEntity;

class ConcertoEntityRepositoryFactoryTest extends ConcertoTestCase
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $c;

    function setUp()
    {
        $this->c = $this->createTestConductor();
    }

    /**
     * @param string $ECN the Entity Class Name
     * @param string $RCN the Repository Class Name
     *
     * @dataProvider threeTypesOfEntityProvider
     */
    function testItCreatesTheCorrectRepoFromGivenEntityName($ECN, $RCN)
    {
        $sut = new ConcertoEntityRepositoryFactory();
        $repo = $sut->getRepository($this->c, $ECN);
        $this->assertInstanceOf($RCN, $repo);
    }
}