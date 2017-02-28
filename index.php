<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

/** @var Symfony\Component\DependencyInjection\ContainerBuilder $sc */
$sc = include __DIR__ . '/Botter/container.php';

$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Response $response */
$response = $sc->get('framework')->handle($request);

$response->send();