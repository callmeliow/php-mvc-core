<?php

namespace callmeliow\phpmvc;

use callmeliow\phpmvc\db\Database;
use callmeliow\phpmvc\db\DbModel;
use Exception;

/**
 * Class Appication
 * 
 * @author Liow Zhi Hao <zhdeveloper0605@gmail.com>
 * @package callmeliow\phpmvc
 */

class Application
{
    public const EVENT_BEFORE_REQUEST = 'beforeRequest';
    public const EVENT_AFTER_REQUEST = 'afterRequest';

    public static string $ROOT_PATH;

    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;
    public Database $db;
    public ?DbModel $user;
    public Session $session;
    public View $view;
    public static Application $app;

    public string $userClass;
    protected array $eventListeners = [];

    public function __construct($rootPath, array $config)
    {
        self::$ROOT_PATH = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->view = new View();
        self::$app = $this;

        $this->userClass = $config['userClass'];
        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');

        if ($primaryValue) {
            $primaryKey = $this->userClass::primarykey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user::primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }

    public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];

        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }
}
