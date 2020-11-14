<?php

require "../vendor/autoload.php";

use Framework\App;
use Symfony\Component\HttpFoundation\Request;
use App\Blog\BlogModule;
use DI\ContainerBuilder;

$modules = [
    BlogModule::class
];

$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__)."/config/config.php");

foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

$container = $builder->build();

$app = new App($container, $modules);

$response = $app->run(Request::createFromGlobals());
$response->send();
