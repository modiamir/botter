<?php

namespace Botter\Factory;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader as RouteYamlFileLoader;

class RouteCollectionFactory
{
    public static function createRouteCollection()
    {
        $locator = new FileLocator(array(__DIR__ . '/../../config'));
        $loader = new RouteYamlFileLoader($locator);

        return $loader->load('routes.yml');
    }
}