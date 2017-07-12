<?php
namespace Tests\unit;

/**
 * @coversDefaultClass \IVAgafonov\System\Router
 */
class RouterTest extends \Codeception\Test\Unit
{
    /**
     * @var \
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testRouterWithValidParamsV1()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithNonExistentControllerFactory()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'api/unknown-controller/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\NonExistentControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":1,"text":"Router: Controller not found"}}');

        $router = new \IVAgafonov\System\Router($config);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("UnIndex");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithNonExistentControllerFactoryV1()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'api/v1/unknown-controller/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'UnknownController' => '\IVAgafonov\Controller\v1\Factory\NonExistentControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":1,"text":"Router: Controller not found"}}');

        $router = new \IVAgafonov\System\Router($config);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("UnknownController");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithInvalidRouterConfigV1()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'api/v1/unknown-controller/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'UnknownController' => '\IVAgafonov\Controller\v1\Factory\NonExistentControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":1,"text":"Router: Controller not found"}}');

        $router = new \IVAgafonov\System\Router($config);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("UnknownController");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithInvalidFactory()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\IndexController'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":2,"text":"Router: Controller not found"}}');

        $router = new \IVAgafonov\System\Router($config);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithInvalidAction()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'api/v1/index/unknown';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":5,"text":"Router: Action not found"}}');

        $router = new \IVAgafonov\System\Router($config);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("unindex");

        $router->run();
    }

    /*
    public function testRouterWithEmptyRequestMethod()
    {
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":11,"text":"Router: Invalid request method"}}');

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithInvalidRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GETS';
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":405,"text":"Router: Method not allowed"}}');

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("GETS");
        $router->setApiVersion("");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }
    */

    public function testRouterWithValidParamsPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $_POST['testPost'] = 'testPost';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("POST");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithValidParamsPostMethodEmptyParams()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("POST");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }

    public function testRouterWithValidParamsPutMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['PATH_INFO'] = 'api/v1/index/index';

        $_POST['testPost'] = 'testPost';

        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("PUT");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");

        $router->run();
    }

    public function testGettersAndSetters()
    {
        $config = [
            'Router' => [
                'Controller' => [
                    'v1' => [
                        'Factory' => [
                            'Index' => '\IVAgafonov\Controller\v1\Factory\IndexControllerFactory'
                        ]
                    ]
                ]
            ]
        ];

        $router = new \IVAgafonov\System\Router($config);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        $router->setMethod("GET");
        $router->setApiVersion("v1");
        $router->setController("Index");
        $router->setAction("index");
        $router->setParams(['a' => 'a']);
        $this->assertEquals("GET", $router->getMethod());
        $this->assertEquals("v1", $router->getApiVersion());
        $this->assertEquals("Index", $router->getController());
        $this->assertEquals("index", $router->getAction());
        $this->assertEquals(['a' => 'a'], $router->getParams());

        $router->run();
    }
}