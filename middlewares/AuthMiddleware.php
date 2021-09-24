<?php

namespace callmeliow\phpmvc\middlewares;

use callmeliow\phpmvc\Application;
use callmeliow\phpmvc\exception\ForbiddenException;
use callmeliow\phpmvc\middlewares\BaseMiddleware;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $action = [])
    {
        $this->actions = $action;
    }

    public function execute()
    {
        if (is_null(Application::$app->user)) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                Application::$app->controller->setLayout('auth');
                throw new ForbiddenException();
            }
        }
    }
}
