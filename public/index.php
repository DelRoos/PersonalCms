<?php

require "../vendor/autoload.php";

use Framework\App;
use Symfony\Component\HttpFoundation\Request;

$app = new App();

$response = $app->run(Request::createFromGlobals());
$response->send();