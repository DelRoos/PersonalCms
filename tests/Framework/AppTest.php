<?php

namespace Test\Framework;

use Framework\App;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AppTest extends TestCase
{
    public function testRedirectTrailingSlash()
    {
        $app = new App();
        $request = Request::create("/delano/", "GET");
        $reponse = $app->run($request);
        $this->assertEquals("/delano", $reponse->headers->get("Location"));
        $this->assertEquals(301, $reponse->getStatusCode());
    }

    public function testBlog()
    {
        $app = new App();
        $request = Request::create('/blog', 'GET');
        $response = $app->run($request);
        $this->assertEquals("<h1>Bienvennue sur mon Blog</h1>", (string)$response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    public function testError404()
    {
        $app = new App();
        $request = Request::create('/aze', 'GET');
        $response = $app->run($request);
        $this->assertEquals("<h1>Erreur 404</h1>", (string)$response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }
}