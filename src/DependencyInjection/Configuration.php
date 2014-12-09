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
                    ->validate()
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
                        ->validate()
                            ->ifTrue(function($v) { return is_string($v) && substr($v, 0, 1) == "@"; } )
                            ->then(  function($v) { return [ 'service' => $v ]; } )
                        ->end()
                        ->validate()
                            ->always() //everything should be an array now
                            ->then(function($v) use($useIfThere_setNullOtherwise) {
                                $useIfThere_setNullOtherwise($v, 'class');
                                $useIfThere_setNullOtherwise($v, 'arguments');
                                $useIfThere_setNullOtherwise($v, 'service');
                                return $v;
                            })
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v['class'] === null && $v['service'] === null; })
                            ->then(  function($v) {
                                list($maybePredefinedSolo, $maybePredefinedArgs)
                                    = $this->checkSolosFor($this->chosenSoloName);
                                $args = array_merge($maybePredefinedArgs, $v['arguments']);
                                return [ 'class'     => $maybePredefinedSolo,
                                         'arguments' => $args,
                                         'service'   => $v['service'] ];
                            })
                        ->end()
                        ->validate()
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
        $definedSolos = [ 'hostname'   => 'Ctrl\Bundle\ConcertoBundle\Solo\HostnameSolo' ,
                          'repository' => 'Ctrl\Bundle\ConcertoBundle\Solo\RepositorySolo' , ];

        $definedArgs  = [ 'hostname'   => '@concerto.soloist_repository', ];

        $retSolo = array_key_exists($name, $definedSolos) ? $definedSolos[$name] : null;
        $retArgs = array_key_exists($name, $definedArgs)  ? [$definedArgs[$name]]  : [];

        return [$retSolo, $retArgs];
    }
}
