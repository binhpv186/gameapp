<?php
namespace base;

class Router
{
    protected $controller;

    protected $action;

    protected $_routeParams = array();

    protected $_rules = array();

    public function __construct()
    {
        $rules = Config::get('router');
        if($rules) {
            $rules = array_merge($this->_rules, $rules);
        }
        $r = array();
        foreach ($rules as $k => $v) {
            $m = str_replace('/', '\/', trim(preg_replace('/\<(\w+)[:]*([^\>]*)\>/', "(?P<$1>$2)", $k), '/'));
            $r[$m] = $v;
        }
        $this->_rules = $r;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getParams()
    {
        return $this->_routeParams;
    }

    public function init()
    {

    }

    public function parseRoute(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        var_dump($this->_rules);
        foreach ($this->_rules as $name => $route) {
            var_dump($name);
            echo $pathInfo;
            $pattern = "/^$name/";
            var_dump($pattern);
            if(preg_match($pattern, $pathInfo, $matches)) {
                $this->controller = dirname($route);
                $this->action = basename($route);
                foreach ($matches as $i=>$v) {
                    if(!is_numeric($i)) {
                        $this->_routeParams[$i] = $v;
                    }
                }
            } else {
                echo 2;
            }
        }
    }
}