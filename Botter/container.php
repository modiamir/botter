<?php

use Botter\Factory\EntityManagerFactory;
use Botter\Factory\RouteCollectionFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$sc = new DependencyInjection\ContainerBuilder();

$definition = new Definition(RouteCollection::class);
$definition->setFactory([RouteCollectionFactory::class, 'createRouteCollection']);
$sc->setDefinition('routes', $definition);

$sc->register('context', 'Symfony\Component\Routing\RequestContext');
$sc->register('matcher', 'Botter\BotRouteMatcher')
    ->setArguments(array(new Reference('routes'), new Reference('context')))
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
        $sc->getParameterBag(),
        'Bot/Model',
        true
    ]
);
$sc->setDefinition('entity_manager', $definition);

$loader = new YamlFileLoader($sc, new FileLocator(__DIR__.'/../'));
$loader->load('config/services.yml');

return $sc;