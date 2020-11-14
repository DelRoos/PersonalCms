<?php

namespace Framework;

use Exception;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    private $modules = [];
    private $container;

    /**
     * load alls module
     *
     * @param array $modules table with content a module
     */
    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->container = $container;

        foreach ($modules as $module) {
            $this->modules[] = $this->container->get($module);
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

        $router = $this->container->get(Router::class);
        $route = $router->match($request);

        if (is_null($route)) {
            return new Response("<h1>Erreur 404</h1>", 404, ["Content-Type", "text/html; charset=utf-8"]);
        }
        
        $request = $this->sendParamsInRequest($route->getParams(), $request);
        $callback = $route->getCallback();
        if (is_string($callback)) {
            $callback = $this->container->get($callback);
        }
        $response = call_user_func_array($callback, [$request]);
        
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
