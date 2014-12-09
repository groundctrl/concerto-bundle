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
}
