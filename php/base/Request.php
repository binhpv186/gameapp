<?php
namespace base;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\ServerRequestFactory;

class Request implements ServerRequestInterface
{
    private $_baseUrl;

    private $_scriptUrl;

    private $_params = array();

    private $_severRequest;

    public function __construct()
    {
        $query_str = explode('?', $_SERVER['REQUEST_URI']);
        $query_str = isset($query_str[1]) ? $query_str[1] : '';
        parse_str($query_str, $query_arr);
        if(is_array($query_arr)) {
            $this->setParams($query_arr);
        }
        $this->_severRequest = ServerRequestFactory::fromGlobals();
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

    /**
     * @inheritdoc
     */
    public function getProtocolVersion()
    {
        return $this->_severRequest->getProtocolVersion();
    }

    /**
     * @inheritdoc
     */
    public function withProtocolVersion($version)
    {
        return $this->_severRequest->withProtocolVersion($version);
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->_severRequest->getHeaders();
    }

    /**
     * @inheritdoc
     */
    public function hasHeader($name)
    {
        return $this->_severRequest->hasHeader($name);
    }

    /**
     * @inheritdoc
     */
    public function getHeader($name)
    {
        return $this->_severRequest->getHeader($name);
    }

    /**
     * @inheritdoc
     */
    public function getHeaderLine($name)
    {
        return $this->_severRequest->getHeaderLine($name);
    }

    /**
     * @inheritdoc
     */
    public function withHeader($name, $value)
    {
        return $this->_severRequest->withHeader($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function withAddedHeader($name, $value)
    {
        return $this->_severRequest->withAddedHeader($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function withoutHeader($name)
    {
        return $this->_severRequest->withoutHeader($name);
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->_severRequest->getBody();
    }

    /**
     * @inheritdoc
     */
    public function withBody(StreamInterface $body)
    {
        return $this->_severRequest->withBody($body);
    }

    /**
     * @inheritdoc
     */
    public function getRequestTarget()
    {
        return $this->_severRequest->getRequestTarget();
    }

    /**
     * @inheritdoc
     */
    public function withRequestTarget($requestTarget)
    {
        return $this->_severRequest->withRequestTarget($requestTarget);
    }

    /**
     * @inheritdoc
     */
    public function withMethod($method)
    {
        return $this->_severRequest->withMethod($method);
    }

    /**
     * @inheritdoc
     */
    public function getUri()
    {
        return $this->_severRequest->getUri();
    }

    /**
     * @inheritdoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return $this->_severRequest->withUri($uri, $preserveHost);
    }

    /**
     * @inheritdoc
     */
    public function getServerParams()
    {
        return $this->_severRequest->getServerParams();
    }

    /**
     * @inheritdoc
     */
    public function getCookieParams()
    {
        return $this->_severRequest->getCookieParams();
    }

    /**
     * @inheritdoc
     */
    public function withCookieParams(array $cookies)
    {
        return $this->_severRequest->withCookieParams($cookies);
    }

    /**
     * @inheritdoc
     */
    public function getQueryParams()
    {
        return $this->_severRequest->getQueryParams();
    }

    /**
     * @inheritdoc
     */
    public function withQueryParams(array $query)
    {
        return $this->_severRequest->withQueryParams($query);
    }

    /**
     * @inheritdoc
     */
    public function getUploadedFiles()
    {
        return $this->_severRequest->getUploadedFiles();
    }

    /**
     * @inheritdoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return $this->_severRequest->withUploadedFiles($uploadedFiles);
    }

    /**
     * @inheritdoc
     */
    public function getParsedBody()
    {
        return $this->_severRequest->getParsedBody();
    }

    /**
     * @inheritdoc
     */
    public function withParsedBody($data)
    {
        return $this->_severRequest->withParsedBody($data);
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return $this->_severRequest->getAttributes();
    }

    /**
     * @inheritdoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->_severRequest->getAttributes($name, $default);
    }

    /**
     * @inheritdoc
     */
    public function withAttribute($name, $value)
    {
        return $this->_severRequest->withAttribute($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function withoutAttribute($name)
    {
        return $this->_severRequest->withoutAttribute($name);
    }
}