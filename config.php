<?php

require_once __DIR__.'/vendor/autoload.php';


use Symfony\Component\Config\FileLocator;

$configDirectories = array(__DIR__.'/config');

$locator = new FileLocator($configDirectories);
$yamlUserFiles = $locator->locate('parameters.yml', null, false);

dump($yamlUserFiles);
