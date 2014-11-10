<?php

namespace Ctrl\Bundle\ConcertoBundle;

use Ctrl\Bundle\ConcertoBundle\DependencyInjection\Compiler\ConcertoCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ConcertoBundle
 *
 * Multi-tenancy for PHP with Symfony.
 */
class CtrlConcertoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConcertoCompilerPass());
    }
}
