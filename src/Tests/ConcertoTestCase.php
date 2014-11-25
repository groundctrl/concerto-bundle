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

    function fourTypesOfEntityProvider()
    {
        //ECN: EntityClassName
        //RCN: RepositoryClassName

        $awareECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity';
        $awareRCN = 'Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository';

        $customAwareECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestCustomAwareEntity';
        $customAwareRCN =
            'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoTestCustomAwareEntityRepository';

        $customUnawareECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestCustomUnawareEntity';
        $customUnawareRCN =
            'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoTestCustomUnawareEntityRepository';

        $unawareECN = 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestUnawareEntity';
        $unawareRCN = 'Doctrine\ORM\EntityRepository';

        return [
            [         $awareECN,         $awareRCN ],
            [   $customAwareECN,   $customAwareRCN ],
            [ $customUnawareECN, $customUnawareRCN ],
            [       $unawareECN,       $unawareRCN ]
        ];
    }
}