<?php

namespace Test\Framework;

use PHPUnit\Framework\TestCase;
use Framework\Renderer\PHPRenderer;

class RendererTest extends TestCase
{
    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer();
        $this->renderer->addPath(__DIR__."/views");
    }

    public function testRenderTheRightPath()
    {
        $this->renderer->addPath("blog", __DIR__."/views");
        $content = $this->renderer->render("@blog/demo");

        $this->assertEquals("je viens de subir un choc violent", $content);
    }

    public function testRenderTheDefaultPath()
    {
        $content = $this->renderer->render("demo");

        $this->assertEquals("je viens de subir un choc violent", $content);
    }


    public function testRenderWithParams()
    {
        $content = $this->renderer->render("demoParams", ["nom"=>"Delano Roosvelt"]);

        $this->assertEquals("salut Delano Roosvelt", $content);
    }

    public function testDefaultParams()
    {
        $content = $this->renderer->addGlobal("nom", "Delano Roosvelt");
        $content = $this->renderer->render("demoParams");
        $this->assertEquals("salut Delano Roosvelt", $content);
    }
}
