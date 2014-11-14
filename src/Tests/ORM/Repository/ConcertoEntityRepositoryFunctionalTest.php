<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\ORM\Repository;

use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoWebTestCase;

class ConcertoEntityRepositoryFunctionalTest extends ConcertoWebTestCase
{
    /**
     * @dataProvider someDomains
     */
    public function testFindReturnsTheCorrectEntity($id, $domainName)
    {
        $this->loadFixtures([ 'Ctrl\Bundle\ConcertoBundle\DataFixtures\ORM\LoadHostnameData' ]);
        $this->assertEquals($domainName, $this->em->find('CtrlConcertoBundle:HostnameSoloist', $id)->getDomain());
    }
}
