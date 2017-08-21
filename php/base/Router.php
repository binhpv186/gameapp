<?php
namespace base;

class Router
{
    private $_controller = 'index';

    private $_action = 'index';

    private $_method;

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
            $m = str_replace('/', '\/', trim(preg_replace('/\<(\w+)[:]*([^\>]*)\>/', "(?<$1>$2)", $k), '/'));
            $r[$m] = $v;
        }
        $this->_rules = $r;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->_action = $action;
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

        if($pathInfo == '') {
            return;
        }

        //Defined Routes
        foreach ($this->_rules as $name => $route) {
            if(preg_match("/^$name/", $pathInfo, $matches)) {
                $route = explode(' ', $route);
                if(count($route) == 2) {
                    $method = explode(',', $route[0]);
                    $route = $route[1];
                } elseif (count($route) == 1) {
                    $method = array('GET');
                    $route = $route[0];
                } else {
                    throw new \Exception('Config routes error');
                }

                if(in_array($request->getMethod(), $method)) {
                    $this->_controller = dirname($route);
                    $this->_action = basename($route);
                    $param = array();
                    foreach ($matches as $i=>$v) {
                        if(!is_numeric($i)) {
                            $param[$i] = $v;
                        }
                    }
                    if(!empty($param)) {
                        $request->setParams($param);
                    }
                } else {
                    throw new \Exception('Not access', 503);
                }
                return;
            }
        }

        //Default Routes
        //Example: Controller/Controller/action/param1/value1/param2/value2/param3/value3/...

        $matched = false;
        $uri = str_replace('/', '\\', $pathInfo);
        while($matched === false && $uri !== 'app\\controllers\\.' && $uri !== '' && $uri !== '.') {
            $controller =  'app\\controllers\\' . dirname($uri). 'Controller';
            if(class_exists($controller)) {
                $this->_controller = dirname($uri) . 'Controller';
                $this->_action = basename($uri);
                $param_str = trim(str_replace(str_replace('\\', '/', $uri), '', $pathInfo), '/');
                $param_arr = explode('/', $param_str);
                $param = array();
                while (isset($param_arr[0]) && isset($param_arr[1])) {
                    $param[$param_arr[0]] = $param_arr[1];
                    $param_arr = array_slice($param_arr, 2);
                }
                if(!empty($param)) {
                    $request->setParams($param);
                }
                $matched = true;
            } else {
                $uri =  dirname($uri);
            }
        }

        if($matched === true) {
            return;
        } else {
            throw new \Exception('Url Not Found', 404);
        }
    }
}