<?php

namespace callmeliow\phpmvc;

use callmeliow\phpmvc\middlewares\BaseMiddleware;

class Controller
{
    public const DEFAULT_PAGE = 'main';

    public string $layout = self::DEFAULT_PAGE;
    public string $action = '';

    /**
     * @var \callmeliow\phpmvc\middlewares\BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
