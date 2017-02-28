<?php

namespace Botter\Factory;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;

class EntityManagerFactory
{
    public static function create(EnvPlaceholderParameterBag $parameterBag, $modelDirectory = 'Model', $isDevMode = true)
    {
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../../".$modelDirectory), $isDevMode);

        die(dump($parameterBag));
        // database configuration parameters
        $conn = array(
            'dbname' => $parameterBag->get('db_name'),
            'user' => $parameterBag->get('db_user'),
            'password' => $parameterBag->get('db_pass'),
            'host' => $parameterBag->get('db_host'),
            'driver' => 'pdo_pgsql',
            'port' => $parameterBag->get('db_port'),
        );

        // obtaining the entity manager
        $entityManager = EntityManager::create($conn, $config);

        return $entityManager;
    }
}