<?php

namespace Test\Framework;

use App\Blog\BlogModule;
use Framework\App;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Framework\Modules\ErroredModule;
use Test\Framework\Modules\StringModule;

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
        $app = new App([
            BlogModule::class
        ]);
        $request = Request::create('/blog', 'GET');
        $response = $app->run($request);
        
        $this->assertEquals("<h1>Bienvennue sur mon Blog</h1>", (string)$response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        $requestSingle = Request::create('/blog/article-de-12', 'GET');
        $responseSingle = $app->run($requestSingle);
        $this->assertEquals("<h1>Bienvennue sur l'artice article-de-12 </h1>", (string) $responseSingle->getContent());
    }
    
    public function testThrowExceptionIfNoResponseSend()
    {
        $app = new App([
            ErroredModule::class
        ]);

        $request = Request::create('/demo', 'GET');
        $this->expectException(\Exception::class);
        $app->run($request);
    }
    
    public function testToConvertStringToResponse()
    {
        $app = new App([
            StringModule::class
        ]);

        $request = Request::create('/demo', 'GET');
        $response = $app->run($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("DEMO", (string)$response->getContent());
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
