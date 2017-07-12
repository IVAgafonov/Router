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
    protected $apiVersion = '';

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
    protected $config = [];

    /**
     * Router constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * Run router
     */
    public function run()
    {
        if (empty($this->config['Router']['Controller'][$this->apiVersion]['Factory'][$this->controller])) {
            header("HTTP/1.1 404 Not found");
            throw new \Exception(json_encode(['error' => ['code' => 1, 'text' => 'Router: Controller not found']]));
        }
        $controllerFactoryClass = $this->config['Router']['Controller'][$this->apiVersion]['Factory'][$this->controller];

        if (!class_exists($controllerFactoryClass)) {
            header("HTTP/1.1 404 Not found");
            throw new \Exception(json_encode(['error' => ['code' => 1, 'text' => 'Router: Controller not found']]));
        }

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
     * @param string $apiVersion
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
