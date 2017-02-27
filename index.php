<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader as RouteYamlFileLoader;

$sc = include __DIR__ . '/Botter/container.php';
$loader = new YamlFileLoader($sc, new FileLocator(__DIR__));
$loader->load('config/services.yml');

$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Response $response */
$response = $sc->get('framework')->handle($request);

$response->send();