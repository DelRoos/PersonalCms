<?php

namespace App\Blog\Action;

use Framework\Renderer\RendererInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogAction
{
    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request)
    {
        $slug = $request->attributes->get("slug");

        if ($slug) {
            return $this->show($slug);
        }
    
        return $this->index();
    }

    public function index(): string
    {
        return $this->renderer->render("@blog/index");
    }

    public function show(string $slug): string
    {
        return $this->renderer->render("@blog/show", [
                "slug" => $slug
            ]);
    }
}
