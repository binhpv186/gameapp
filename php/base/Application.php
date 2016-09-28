<?php
namespace base;

use base\Request;
class Application
{
    protected $request;

    protected $router;

    private $controller = 'index';

    private $action = 'index';

    public function __construct()
    {
        $this->request = new Request();
        $this->router = new Router();
    }

    public function run()
    {
//        echo $this->getBaseUrl() . '<br/>';
//        echo $_GET['url'] . '<br/>';
        $route = $this->getRoute();
//        echo APP_PATH;
        $controller_file = APP_PATH . 'controllers/'.ucfirst($route[0]).'Controller.php';
        if(file_exists($controller_file)) {
//            echo $controller_file;
            $controller_name = 'app\controllers\\'.ucfirst($route[0]).'Controller';
            $controller = new $controller_name;
            if(method_exists($controller, ucfirst($route[1]).'Action')) {
                call_user_func(array($controller, ucfirst($route[1]).'Action'));
            } else {
//                echo 2;
            }
        } else {
//            echo 3;
        }

//        var_dump($this->getRoute());
    }

    public function getBaseUrl()
    {
        return $this->request->getBaseUrl();
    }

    public function getRoute()
    {
        return $this->request->parseUrl();
    }
}