<?php

namespace Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    public function run(Request $request): Response
    {
        $uri = $request->getPathInfo();
        
        if (!empty($uri) && $uri[-1]=="/") {
            return new Response("", 301, ['Location' => substr($uri, 0, -1)]);
        }

        if ($uri === "/blog") {
            return new Response("<h1>Bienvennue sur mon Blog</h1>", 200, ["Content-Type" => "text/html; charset=utf-8"]);
        }

        return new Response("<h1>Erreur 404</h1>", 404, ["Content-Type", "text/html; charset=utf-8"]);
    }
}
