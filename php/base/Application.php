<?php
namespace base;

class Application
{
    public $request;

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
        try {
            $this->router->parseRoute($this->request);
            $controller =  'app\\controllers\\'. str_replace('Controller', '', $this->router->getController()).'Controller';
            if(class_exists($controller)) {
                $action_name = $this->router->getAction().'Action';
                $controller = new $controller;
                if(method_exists($controller, $action_name)) {
                    call_user_func(array($controller, $action_name));
                } else {
                    throw new \Exception('Action Not Found!', 404);
                }
            } else {
                throw new \Exception('Controller Not Found!', 404);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
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