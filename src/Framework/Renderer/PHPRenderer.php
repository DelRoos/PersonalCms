<?php

namespace Framework\Renderer;

use Framework\Renderer\RendererInterface;

class PHPRenderer implements RendererInterface
{
    const DEFAULT_NAMESPACE = "__TEMPLATE_DEFAULT__";
    private $paths = [];
    private $globals = [];

    public function __construct(?string $defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

    public function addPath(string $namesapce, ?string $path = null): void
    {
        is_null($path) ? $this->paths[self::DEFAULT_NAMESPACE] = $namesapce : $this->paths[$namesapce] = $path;
    }
    
    public function render(string $view, ?array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNameSpace($view).".php";
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE].DIRECTORY_SEPARATOR.$view.".php";
        }

        ob_start();
        $renderer = $this;
        extract($this->globals);
        extract($params);
        require($path);
        return ob_get_clean();
    }

    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    private function hasNamespace(string $views): bool
    {
        return $views[0] === "@";
    }

    private function getNameSpace(string $view): string
    {
        return substr($view, 1, strpos($view, "/")-1);
    }

    private function replaceNameSpace(string $view): string
    {
        $namespace = $this->getNameSpace($view);
        return str_replace("@".$namespace, $this->paths[$namespace], $view);
    }
}
