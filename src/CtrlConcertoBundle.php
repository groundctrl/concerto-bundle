<?php

namespace Ctrl\Bundle\ConcertoBundle;

use Ctrl\Bundle\ConcertoBundle\DependencyInjection\Compiler\ConcertoCompilerPass;
use Ctrl\Bundle\ConcertoBundle\DependencyInjection\ConcertoExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ConcertoBundle
 *
 * Multi-tenancy for PHP with Symfony.
 */
class CtrlConcertoBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConcertoCompilerPass());
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new ConcertoExtension();
    }
}
