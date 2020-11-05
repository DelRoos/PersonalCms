<?php

namespace Test\Framework;

use Framework\Router;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends TestCase
{
    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = Request::create('/blog', 'GET');
        $this->router->get(
            '/blog',
            function () {
                return "hello gies";
            },
            'blog'
        );
        
        $route = $this->router->match($request);
        
        $this->assertEquals(
            'blog',
            $route->getName()
        );
        
        $this->assertEquals(
            'hello gies',
            call_user_func_array($route->getCallback(), [$request])
        );
    }

    public function testGetMethodIfURLDoesNotExist()
    {
        $request = Request::create('/blog', 'GET');
        
        $this->router->get(
            '/blogaze',
            function () {
                return "hello";
            },
            'blog'
        );

        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParameters()
    {
        $request = Request::create('/blog/mon-slug-10', 'GET');
        
        $this->router->get(
            '/blog',
            function () {
                return "azeaze";
            },
            'post'
        );
        
        $this->router->get(
            '/blog/{slug<[a-z0-9\-]+>}-{id<\d+>}',
            function () {
                return "hello";
            },
            'posts.show'
        );
        
        $route = $this->router->match($request);
        
        $this->assertEquals(
            'posts.show',
            $route->getName()
        );
        
        $this->assertEquals(
            'hello',
            call_user_func_array($route->getCallback(), [$request])
        );

        $this->assertEquals(
            ['slug'=>'mon-slug', 'id'=>'10'],
            $route->getParams()
        );

        $route = $this->router->match(
            Request::create('/blog/mon_slug-8', "GET")
        );
        $this->assertEquals(
            null,
            $route
        );
    }

    public function testGenerateUri()
    {
        $this->router->get(
            '/blog',
            function () {
                return "azeaze";
            },
            'post'
        );
        
        $this->router->get(
            '/blog/{slug<[a-z0-9\-]+>}-{id<\d+>}',
            function () {
                return "hello";
            },
            'posts.show'
        );

        $uri = $this->router->generateUri("posts.show", ["slug"=>"mon-article", "id"=>'18']);
        $this->assertEquals("/blog/mon-article-18", $uri);
    }
}
