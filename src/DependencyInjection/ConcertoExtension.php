<?php

namespace Ctrl\Bundle\ConcertoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ConcertoExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $config = $this->processConfiguration($configuration, $configs);

        $chosenSolo = $config['solo_name'];

        $serviceStringOrArrayToReference = function($x) use (&$serviceStringOrArrayToReference) {
            if(is_string($x) && substr($x, 0, 1) == "@") {
                return new Reference(substr($x, 1));
            } elseif(is_array($x)) {
                return array_map($serviceStringOrArrayToReference, $x);
            } return $x;
        };

        $chosenSoloCfg = array_map($serviceStringOrArrayToReference, $config['solos'][$chosenSolo]);
        unset($config['solos']);
        $config['solo'] = $chosenSoloCfg;

        $solo = null;
        $id = $chosenSoloCfg['service'];

        if($id != null) {

            if($container->hasDefinition($id)) {

                $solo = $container->getDefinition($id);

            } else throw new \InvalidArgumentException('Could not find service ' . $id);

        } else {

          $solo = new Definition($chosenSoloCfg['class'], $chosenSoloCfg['arguments']);
        }

        $container->setDefinition('concerto.solo', $solo);
        $container->setParameter('concerto.soloist_class', $config['soloist_class']);
        $container->setParameter('concerto.solo_name', $config['solo_name']);
        $container->setParameter('concerto.solo.class', $chosenSoloCfg['class']);
    }
}
