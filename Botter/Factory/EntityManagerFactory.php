<?php

namespace Botter\Factory;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    public function create($modelDirectory = 'Model', $isDevMode = true)
    {
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../../".$modelDirectory), $isDevMode);
        // or if you prefer yaml or XML
        //$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
        //$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

        // database configuration parameters
        $conn = array(
            'dbname' => 'botter',
            'user' => 'botter',
            'password' => 'botter',
            'host' => 'localhost',
            'driver' => 'pdo_pgsql',
            'port' => '4321',
        );

        // obtaining the entity manager
        $entityManager = EntityManager::create($conn, $config);

        return $entityManager;
    }
}