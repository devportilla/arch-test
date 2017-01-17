<?php

namespace Ulatest\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Ulatest\UlatestMarketingBundle;

final class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SimpleBusCommandBusBundle(),
            new DoctrineBundle(),

            new UlatestMarketingBundle(),
        ];
    }

    public function getRootDir()
    {
        return realpath(__DIR__ . '/..');
    }

    public function getCacheDir()
    {
        return $this->getRootDir() . '/var/cache';
    }

    public function getLogDir()
    {
        return $this->getRootDir() . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
