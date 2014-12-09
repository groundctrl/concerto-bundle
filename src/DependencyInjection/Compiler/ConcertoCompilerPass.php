<?php

namespace Ctrl\Bundle\ConcertoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class ConcertoCompilerPass
 *
 * Does some Symfony container configuring.
 *   - Replace doctrine's entity manager with our own.
 */
class ConcertoCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('doctrine.orm.entity_manager.class') == 'Doctrine\\ORM\\EntityManager') {
            $container->setParameter('doctrine.orm.entity_manager.class', 'Ctrl\Bundle\ConcertoBundle\ORM\Conductor');
        }
    }
}
