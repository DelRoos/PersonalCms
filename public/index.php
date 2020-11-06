<?php

require "../vendor/autoload.php";

use Framework\App;
use Symfony\Component\HttpFoundation\Request;
use App\Blog\BlogModule;
use Framework\Renderer;

$renderer = new Renderer();
$renderer->addPath(dirname(__DIR__)."/views");

$app = new App([
    BlogModule::class
], [
    "renderer" => $renderer
]);

$response = $app->run(Request::createFromGlobals());
$response->send();
