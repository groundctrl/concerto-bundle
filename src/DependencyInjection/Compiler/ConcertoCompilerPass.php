<?php

namespace Ctrl\Bundle\ConcertoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class ConcertoCompilerPass
 *
 * Does some standard Symfony configuring.
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

        if ($container->hasDefinition('doctrine.orm.default_entity_manager')) {
            $reference = $container->getDefinition('doctrine.orm.default_entity_manager');

            $reference->addMethodCall('setConcertoRepositoryClassName', [
                $container->getParameter('concerto.repository.default_class')
            ]);
        }
    }
}
