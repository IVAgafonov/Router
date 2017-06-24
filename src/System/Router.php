<?php

namespace IVAgafonov\System;

/**
 * Class Router
 * @package IVAgafonov\System
 */
class Router implements RouterInterface
{

    /**
     * @var string
     */
    protected $apiVersion = 'v1';

    /**
     * @var string
     */
    protected $controller  = 'Index';

    /**
     * @var string
     */
    protected $action = 'index';

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $server = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Router constructor.
     *
     * @param array $config
     * @param array $server
     */
    public function __construct($config = [], $server = [])
    {
        session_start();
        header("Content-Type: application/json; charset=utf-8");
        $this->server = empty($server) ? $_SERVER : $server;
        $this->config = $config;
    }

    /**
     * Run router
     */
    public function run()
    {
        $this->dispatch();
        $this->parseParams();

        if (empty($this->config['Router']['Controller']['Factory'][$this->controller])
            || !class_exists($this->config['Router']['Controller']['Factory'][$this->controller])
        ) {
            header("HTTP/1.1 404 Not found");
            throw new \Exception(json_encode(['error' => ['code' => 1, 'text' => 'Router: Controller not found']]));
        }
        $controllerFactoryClass = $this->config['Router']['Controller']['Factory'][$this->controller];
        $controllerFactory = new $controllerFactoryClass();
        $factoryInterface = '\IVAgafonov\Controller\Factory\ControllerFactoryInterface';
        if (!$controllerFactory instanceof $factoryInterface) {
            header("HTTP/1.1 404 Not found");
            throw new \Exception(json_encode(['error' => ['code' => 2, 'text' => 'Router: Controller not found']]));
        }
        $controllerObject = $controllerFactory->create($this->config);
        $controllerObject->setMethod($this->method);
        $controllerObject->setParams($this->params);
        if (!method_exists($controllerObject, $this->action)) {
            header("HTTP/1.1 404 Not found");
            throw new \Exception(json_encode(['error' => ['code' => 5, 'text' => 'Router: Action not found']]));
        }
        $action = $this->action;
        $controllerObject->$action();
    }

    /**
     * Dispatch route
     */
    protected function dispatch()
    {
        $this->action = 'index';
        $this->controller = 'Index';
        if (empty($this->server['PATH_INFO'])) {
            header("HTTP/1.1 400 Bad request");
            throw new \Exception(json_encode(['error' => ['code' => 10, 'text' => 'Router: Empty path info']]));
        }

        if (empty($this->server['REQUEST_METHOD'])) {
            header("HTTP/1.1 400 Bad request");
            throw new \Exception(json_encode(['error' => ['code' => 11, 'text' => 'Router: Invalid request method']]));
        }

        if (!in_array(strtoupper($this->server['REQUEST_METHOD']), ['GET', 'POST', 'UPDATE', 'DELETE', 'PUT', 'PATH', 'OPTIONS', 'HEAD'])) {
            header("HTTP/1.1 405 Method not allowed");
            throw new \Exception(json_encode(['error' => ['code' => 405, 'text' => 'Router: Method not allowed']]));
        }

        $this->method = strtoupper($this->server['REQUEST_METHOD']);

        $path = explode("/", $this->server['PATH_INFO']);
        array_shift($path);
        if (is_array($path) && count($path) > 1) {
            if (!empty($path)) {
                $this->apiVersion = strtolower(array_shift($path));
            }
            if (!empty($path)) {
                $this->controller = ucfirst(strtolower(array_shift($path)));
            }
            if (!empty($path)) {
                $this->action = strtolower(array_shift($path));
            }
        }
        $this->controller;
    }

    /**
     * Parse parameters
     */
    protected function parseParams()
    {
        $this->params = [];
        switch($this->method) {
            case 'GET':
                $this->params = $_GET;
                break;
            case 'POST':
                if (empty($_POST)) {
                    $_POST = (array)json_decode(trim(file_get_contents('php://input')), true);
                }
                $this->params = $_POST;
                $this->params = array_merge_recursive($this->params, $_FILES);
                $this->params = array_merge_recursive($this->params, $_GET);
                break;
            default:
                $this->params = (array)json_decode(trim(file_get_contents('php://input')), true);
                $this->params = array_merge_recursive($this->params, $_GET);
        }
        return $this->params;
    }
}
