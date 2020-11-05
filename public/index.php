<?php

require "../vendor/autoload.php";

use Framework\App;
use Symfony\Component\HttpFoundation\Request;
use App\Blog\BlogModule;

$app = new App([
    BlogModule::class
]);

$response = $app->run(Request::createFromGlobals());
$response->send();
