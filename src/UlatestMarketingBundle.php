<?php

namespace Ulatest;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Ulatest\DependencyInjection\UlatestMarketingExtension;

final class UlatestMarketingBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function getContainerExtension()
    {
        return new UlatestMarketingExtension();
    }
}
