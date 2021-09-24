<?php

namespace callmeliow\phpmvc;

/**
 * Class Router
 * 
 * @author Liow Zhi Hao <zhdeveloper0605@gmail.com>
 * @package callmeliow\phpmvc
 */

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        // echo "<pre>";
        // // var_dump($path);
        // var_dump($callback);
        // // var_dump($this->routes);
        // echo "</pre>";

        if ($callback === false) {
            $this->response->setStatusCode(404);
            return Application::$app->view->renderView("_404");
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            /** @var \callmeliow\phpmvc\Controller $controller */
            $controller = new $callback[0]();
            $controller->action = $callback[1];
            Application::$app->controller = $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        // echo "<pre>";
        // var_dump($controller->getMiddlewares());
        // echo "</pre>";
        // exit;

        return call_user_func($callback, $this->request);
    }
}
