<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;
use app\core\middlewares\BaseMiddleware;

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
