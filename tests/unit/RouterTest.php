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

    public function testRouterWithValidParams()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'GET';
        $server['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        session_destroy();
        $router->run();
    }

    public function testRouterWithEmptyParams()
    {
        $server = [];
        $config = [];
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":10,"text":"Router: Empty path info"}}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);
        session_destroy();

        $router->run();
    }

    public function testRouterWithNonExistentControllerFactory()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'GET';
        $server['PATH_INFO'] = 'api/v1/unknown/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\NonExistentControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":1,"text":"Router: Controller not found"}}');

        $router = new \IVAgafonov\System\Router($config, $server);
        session_destroy();

        $router->run();
    }

    public function testRouterWithInvalidFactory()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'GET';
        $server['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\IndexController'
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":2,"text":"Router: Controller not found"}}');

        $router = new \IVAgafonov\System\Router($config, $server);
        session_destroy();

        $router->run();
    }

    public function testRouterWithInvalidAction()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'GET';
        $server['PATH_INFO'] = 'api/v1/index/unknown';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":5,"text":"Router: Action not found"}}');

        $router = new \IVAgafonov\System\Router($config, $server);
        session_destroy();

        $router->run();
    }

    public function testRouterWithEmptyRequestMethod()
    {
        $server = $_SERVER;
        $server['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":11,"text":"Router: Invalid request method"}}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        session_destroy();
        $router->run();
    }

    public function testRouterWithInvalidRequestMethod()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'GETS';
        $server['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('{"error":{"code":405,"text":"Router: Method not allowed"}}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        session_destroy();
        $router->run();
    }

    public function testRouterWithValidParamsPostMethod()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'POST';
        $server['PATH_INFO'] = 'api/v1/index/index';

        $_POST['testPost'] = 'testPost';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        session_destroy();
        $router->run();
    }

    public function testRouterWithValidParamsPostMethodEmptyParams()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'POST';
        $server['PATH_INFO'] = 'api/v1/index/index';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        session_destroy();
        $router->run();
    }

    public function testRouterWithValidParamsPutMethod()
    {
        $server = $_SERVER;
        $server['REQUEST_METHOD'] = 'PUT';
        $server['PATH_INFO'] = 'api/v1/index/index';

        $_POST['testPost'] = 'testPost';

        $config = [
            'Router' => [
                'Controller' => [
                    'Factory' => [
                        'Index' => '\IVAgafonov\Controller\Factory\IndexControllerFactory'
                    ]
                ]
            ]
        ];

        $this->expectOutputString('{"status":"ok"}');

        $router = new \IVAgafonov\System\Router($config, $server);
        $this->assertInstanceOf('\IVAgafonov\System\RouterInterface', $router);

        session_destroy();
        $router->run();
    }
}