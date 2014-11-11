<?php

namespace Ctrl\Bundle\ConcertoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration implements ConfigurationInterface
{
    protected $alias;
    protected $chosenSoloName = null;

    /**
     * @param string $alias
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->alias);

        $rootNode
            ->children()
                ->scalarNode('soloist_class')->end()
                ->scalarNode('solo_name')
                    ->beforeNormalization()
                        ->always()
                        ->then(function($v) {
                            $this->chosenSoloName = $v;
                            return $v;
                        })
                        ->end()
                ->defaultValue('hostname')
                ->end()
            ->end()
        ;

        $this->addSolosSection($rootNode);
        return $treeBuilder;
    }

    private function addSolosSection(ArrayNodeDefinition $rootNode)
    {
        $useIfThere_setNullOtherwise = function(&$ary, $k) {
            if (array_key_exists($k, $ary)) {
                return $ary[$k];
            } else {
                $ary[$k] = null;
                return null;
            }
        };

        $rootNode
            ->children()
                ->arrayNode('solos')
                    ->isRequired()
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifTrue(function($v) { return is_string($v) && substr($v, 0, 1) == "@"; } )
                            ->then(  function($v) { return [ 'service' => $v ]; } )
                        ->end()
                        ->beforeNormalization()
                            ->always() //everything should be an array now
                            ->then(function($v) use($useIfThere_setNullOtherwise) {
                                $useIfThere_setNullOtherwise($v, 'class');
                                $useIfThere_setNullOtherwise($v, 'arguments');
                                $useIfThere_setNullOtherwise($v, 'service');
                                return $v;
                            })
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(function($v) { return $v['class'] === null && $v['service'] === null; })
                            ->then(  function($v) {
                                return [ 'class'     => $this->checkSolosFor($this->chosenSoloName),
                                         'arguments' => $v['arguments'],
                                         'service'   => $v['service'] ];
                            })
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(function($v) { //same ifTrue but now we've checked against /Solo folder
                                return $v['class'] !== null && $v['service'] !== null;
                            })
                            ->thenInvalid("You need to set a valid class and arguments XOR a service. "
                                . "One or the other, not both. Solo info: %s")
                        ->end()
                        ->children()

                            ->scalarNode('service')
                                ->defaultValue(null)
                            ->end()

                            ->scalarNode('class')
                                ->end()

                            ->arrayNode('arguments')
                                ->prototype('variable')->end()
                            ->end()

                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function checkSolosFor($name)
    {
        $defined = [ 'hostname'   => 'Ctrl\Bundle\ConcertoBundle\Solo\HostnameSolo' ,
                     'repository' => 'Ctrl\Bundle\ConcertoBundle\Solo\RepositorySolo' , ];

        return array_key_exists($name, $defined) ? $defined[$name] : null;
    }
}
