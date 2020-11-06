<?php

namespace Framework;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    private $modules = [];
    private $router; //the router with match a request

    /**
     * load alls module
     *
     * @param array $modules table with content a module
     */
    public function __construct(array $modules = [], array $dependencies = [])
    {
        $this->router = new Router();

        if (array_key_exists("renderer", $dependencies)) {
            $dependencies["renderer"]->addGlobal("router", $this->router);
        }
        
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router, $dependencies["renderer"]);
        }
    }

    /**
     * Execute the request
     *
     * @param Request $request request if execute
     * @return Response response of a request
     */
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


    /**
     * send parameters in request
     *
     * @param array $params list of params
     * @param Request $request request with take a params
     * @return Request request content params
     */
    private function sendParamsInRequest(array $params, Request $request): Request
    {
        foreach ($params as $key => $value) {
            $request->attributes->set($key, $value);
        }

        return $request;
    }
}
