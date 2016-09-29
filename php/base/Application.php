<?php
namespace base;

use App;
class Application
{
    public static $app;

    public $request;

    protected $router;

    private $controller = 'index';

    private $action = 'index';

    public function __construct()
    {
        $this->request = new Request();
        $this->router = new Router();
        App::$app = $this;
    }

    public function run()
    {
        $this->router->parseRoute($this->request);
        $controller_file = APP_PATH . 'controllers/'.$this->router->getController().'.php';
        if(file_exists($controller_file)) {
            $controller_name = 'app\controllers\\'.$this->router->getController();
            $action_name = $this->router->getAction().'Action';
            $controller = new $controller_name;
            if(method_exists($controller, $action_name)) {
                $this->request->params = $this->router->getParams();
                call_user_func(array($controller, $action_name));
            } else {
                echo 2;
            }
        } else {
            echo 3;
        }

//        var_dump($this->getRoute());
    }

    public function getBaseUrl()
    {
        return $this->request->getBaseUrl();
    }

    public function getRouter()
    {
        return $this->router;
    }
}