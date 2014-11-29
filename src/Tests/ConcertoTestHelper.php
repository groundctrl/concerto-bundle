<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ctrl\Bundle\ConcertoBundle\Tests;

use Ctrl\Bundle\ConcertoBundle\ORM\Conductor;
use Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepositoryFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * Class ConcertoTestHelper
 *
 * Utility method for unit testing the Entity Manager.
 */
class ConcertoTestHelper
{
    /**
     * Returns an entity manager for testing.
     *
     * @return EntityManager
     */
    public static function createTestConductor()
    {
        if (!class_exists('PDO') || !in_array('mysql', \PDO::getAvailableDrivers())) {
            \PHPUnit_Framework_TestCase::markTestSkipped('This test requires MySQL support in your environment');
        }

        $config = new \Doctrine\ORM\Configuration();
        $config->setEntityNamespaces(array('ConcertoTests' => 'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures'));
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(\sys_get_temp_dir());
        $config->setProxyNamespace('ConcertoTests');
        $config->setMetadataDriverImpl(new AnnotationDriver(new AnnotationReader()));
        $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        //$config->setRepositoryFactory(new ConcertoEntityRepositoryFactory());
        $config->addFilter('soloist', 'Ctrl\Bundle\ConcertoBundle\ORM\Filter\SoloistFilter');
        $params = [
            'driver' => 'pdo_mysql',
            'dbname' => 'groundctrl_test',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
        ];

        $eventManager = new EventManager();

        return Conductor::create($params, $config, $eventManager);
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
