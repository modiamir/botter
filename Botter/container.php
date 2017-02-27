<?php

use Botter\Factory\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\Loader\YamlFileLoader as RouteYamlFileLoader;

$locator = new FileLocator(array(__DIR__ . '/../config'));
$loader = new RouteYamlFileLoader($locator);
$routes = $loader->load('routes.yml');

$sc = new DependencyInjection\ContainerBuilder();
$sc->register('context', 'Symfony\Component\Routing\RequestContext');
$sc->register('matcher', 'Botter\BotRouteMatcher')
    ->setArguments(array($routes, new Reference('context')))
;
$sc->register('request_stack', 'Symfony\Component\HttpFoundation\RequestStack');
$sc->register('resolver', 'Botter\ControllerResolver')
    ->setArguments(array($sc));

$sc->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments(array(new Reference('matcher'), new Reference('request_stack')))
;
$sc->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(array('UTF-8'))
;
$sc->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
    ->setArguments(array('Calendar\\Controller\\ErrorController::exceptionAction'))
;
$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
;
$sc->register('framework', 'Botter\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;

$definition = new Definition(EntityManager::class);
$definition->setFactory(array(EntityManagerFactory::class, 'create'))->setArguments(
    [
        'Bot/Model',
        true
    ]
);
$sc->setDefinition('entity_manager', $definition);

return $sc;
