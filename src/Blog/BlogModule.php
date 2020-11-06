<?php

namespace App\Blog;

use Framework\Renderer;
use Framework\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogModule
{

    private $renderer;

    public function __construct(Router $router, Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->addPath("blog", __DIR__."/views");

        $router->get("/blog", [$this, "index"], "blog.index");
        $router->get("/blog/{slug<[a-z0-9\-]+>}", [$this, "show"], "blog.show");
    }

    public function index(Request $request): string
    {
        return $this->renderer->render("@blog/index");
    }

    public function show(Request $request): string
    {
        return $this->renderer->render("@blog/show", [
                "slug" => $request->attributes->get("slug")
            ]);
    }
}
