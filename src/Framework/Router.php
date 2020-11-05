<?php
namespace Framework;

use Framework\Router\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\RouteCollection;

/**
 * register and match route
 */
class Router
{
    private $router;

    public function __construct()
    {
        $this->router = new RouteCollection();
    }

    public function get(string $path, callable $callback, string $name)
    {
        $this->router->add($name, new RoutingRoute($path, ["callback" => $callback]));
    }

    public function match(Request $request): ?Route
    {
        $context = (new RequestContext())->fromRequest($request);
        $urlMatcher = new UrlMatcher($this->router, $context);

        try {
            $routeParams = $urlMatcher->matchRequest($request);
            $routeName = array_pop($routeParams);
            $routeCallback = array_shift($routeParams);
    
            return new Route($routeName, $routeCallback, $routeParams);
        } catch (ResourceNotFoundException $e) {
            return null;
        }
    }

    public function generateUri(string $name, array $params): ?string
    {
        $context = new RequestContext();
        $generator = new UrlGenerator($this->router, $context);

        return $generator->generate($name, $params);
    }
}
