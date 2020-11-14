<?php

use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Framework\Router\RouterTwigExtension;

use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    "views.path" => dirname(__DIR__)."/views",
    "twig.extension" => [
        get(RouterTwigExtension::class)
    ],
    Router::class => autowire(Router::class),
    RendererInterface::class => factory(TwigRendererFactory::class)
];
