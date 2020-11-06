<?php

require "../vendor/autoload.php";

use Framework\App;
use Symfony\Component\HttpFoundation\Request;
use App\Blog\BlogModule;
use Framework\Renderer\PHPRenderer;
use Framework\Renderer\TwigRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$renderer = new TwigRenderer(dirname(__DIR__)."/views");

// $loader = new FilesystemLoader(dirname(__DIR__)."/views");
// $twig = new Environment($loader, []);

$app = new App([
    BlogModule::class
], [
    "renderer" => $renderer
]);

$response = $app->run(Request::createFromGlobals());
$response->send();
