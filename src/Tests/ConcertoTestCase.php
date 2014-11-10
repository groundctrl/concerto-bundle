<?php

namespace Ctrl\Bundle\ConcertoBundle\tests;


class ConcertoTestCase extends \PHPUnit_Framework_TestCase
{
    use \Xpmock\TestCaseTrait;

    function argTypeCallback($typestr)
    {
        return $this->callback(
            function($x) use($typestr){
                return is_a($x, $typestr);
            });
    }

    public static function createTestConductor()
    {
        return ConcertoTestHelper::createTestConductor();
    }

    function threeTypesOfEntityProvider()
    {
        //ECN: EntityClassName
        //RCN: RepositoryClassName

        $concertoECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoConcertoFakeEntity';
        $concertoRCN = 'Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository';

        $customECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoCustomFakeEntity';
        $customRCN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoFakeEntityCustomRepository';

        $regularECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoRegularFakeEntity';
        $regularRCN = 'Doctrine\ORM\EntityRepository';

        return [
            [ $concertoECN, $concertoRCN ],
            [ $customECN,   $customRCN   ],
            [ $regularECN,  $regularRCN  ]
        ];
    }
}