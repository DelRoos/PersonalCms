<?php

namespace Framework;

use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    private $modules = [];

    private $router;

    public function __construct(array $modules = [])
    {
        $this->router = new Router();

        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router);
        }
    }

    public function run(Request $request): Response
    {
        $uri = $request->getPathInfo();
        
        if (!empty($uri) && $uri[-1]=="/") {
            return new Response("", 301, ['Location' => substr($uri, 0, -1)]);
        }

        $route = $this->router->match($request);

        if (is_null($route)) {
            return new Response("<h1>Erreur 404</h1>", 404, ["Content-Type", "text/html; charset=utf-8"]);
        }
        
        $request = $this->sendParamsInRequest($route->getParams(), $request);
        $response = call_user_func_array($route->getCallback(), [$request]);
        
        if (is_string($response)) {
            return new Response($response, 200, ["Content-Type", "text/html; charset=utf-8"]);
        } elseif ($response instanceof Request) {
            return $response;
        } else {
            throw new Exception("this url did not match");
        }
    }

    private function sendParamsInRequest(array $params, Request $request): Request
    {
        foreach ($params as $key => $value) {
            $request->attributes->set($key, $value);
        }

        return $request;
    }
}
