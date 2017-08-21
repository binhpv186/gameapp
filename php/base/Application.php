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
            $this->parseRoute();
            $controller =  'app\\controllers\\'. ucfirst(str_replace('Controller', '', $this->getController()).'Controller');
            if(class_exists($controller)) {
                $action_name = $this->getAction().'Action';
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
	
	public function parseRoute()
	{
		$this->router->parseRoute($this->request);
	}
	
	public function getController()
	{
		return $this->getRouter()->getController();
	}
	
	public function getAction()
	{
		return $this->getRouter()->getAction();
	}
}