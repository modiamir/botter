<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$sc = include __DIR__ . '/../Botter/container.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($sc->get('entity_manager'));

