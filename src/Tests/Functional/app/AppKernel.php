<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * @return array
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Liip\FunctionalTestBundle\LiipFunctionalTestBundle(),
            new \Ctrl\Bundle\ConcertoBundle\CtrlConcertoBundle()
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/CtrlConcertoBundle/cache';
    }

    /**
     * {@inheritDoc}
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/CtrlConcertoBundle/logs';
    }
} 