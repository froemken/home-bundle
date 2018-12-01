<?php

namespace StefanFroemken\Bundle\HomeBundle;

use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Main class for Symfony Bundles
 */
class HomeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function registerCommands(Application $application)
    {
        // noop
    }
}

