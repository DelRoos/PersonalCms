<?php

namespace App\Blog;

use Framework\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogModule
{

    public function __construct(Router $router)
    {
        $router->get("/blog", [$this, "index"], "blog.index");
        $router->get("/blog/{slug<[a-z0-9\-]+>}", [$this, "show"], "blog.show");
    }

    public function index(Request $request): string
    {
        return "<h1>Bienvennue sur mon Blog</h1>";
    }

    public function show(Request $request): string
    {
        return "<h1>Bienvennue sur l'artice ".$request->attributes->get("slug")." </h1>";
    }
}
