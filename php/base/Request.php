<?php
namespace base;

class Request
{
    private $_baseUrl;

    private $_scriptUrl;

    private $_params = array();

    public function __construct()
    {
        $query_str = explode('?', $_SERVER['REQUEST_URI']);
        $query_str = isset($query_str[1]) ? $query_str[1] : '';
        parse_str($query_str, $query_arr);
        if(is_array($query_arr)) {
            $this->setParams($query_arr);
        }
    }

    public function setParams(Array $params)
    {
        $this->_params = array_merge($this->_params, $params);
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getParam($name, $default = null)
    {
        if(isset($this->_params[$name])) {
            return $this->_params[$name];
        } else {
            return $default;
        }
    }

    public function parseUrl()
    {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(trim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    public function getMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
    }

    public function getPathInfo()
    {
        $pathInfo = '';
        if(isset($_SERVER['REDIRECT_URL'])) {
            $pathInfo = $_SERVER['REDIRECT_URL'];
        } else {
            $pathInfo = explode('?', $_SERVER['REQUEST_URI']);
            $pathInfo = $pathInfo[0];
        }
        return trim(str_replace($this->getBaseUrl(), '', $pathInfo), '/');
    }

    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\/');
        }

        return $this->_baseUrl;
    }

    private function getScriptUrl()
    {
        if ($this->_scriptUrl === null) {
            $scriptFile = $_SERVER['SCRIPT_FILENAME'];
            $scriptName = basename($scriptFile);

            if (basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } elseif (basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } elseif (($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $scriptFile));
            } else {
//                throw new InvalidConfigException('Unable to determine the entry script URL.');
            }
        }

        return $this->_scriptUrl;
    }
}