<?php
namespace base\model;

use base\Config;

class JsonModel
{
    private $_autoload = false;

    private $_dataSegment = 'data';

    private $_dataPath = 'data/';

    private $_content;

    public $name;

    public function __construct()
    {
        $dataPath = Config::get('JsonModel.dataPath');
        if($dataPath) {
            $this->_dataPath = $dataPath;
        } else {
            $this->_dataPath = APP_PATH . $this->_dataPath;
        }
        if($this->_autoload === true) {
            $this->load();
        }
    }

    public function load()
    {
        if (!empty($this->name)) {
            if (file_exists($this->_dataPath.$this->name.'.json')) {
                $this->_content = json_decode(file_get_contents($this->_dataPath.$this->name.'.json'), true);
                if(empty($this->_content)) {
                    throw new \Exception('JsonModel error: empty content');
                }
                if(!isset($this->_content[$this->_dataSegment])) {
                    throw new \Exception('JsonModel error: data schema');
                }
            } else {
                throw new \Exception('Cannot load data file', 404);
            }
        } else {
            throw new \Exception('JsonModel name not defined');
        }
    }

    public function findAll()
    {
        if(empty($this->_content)) {
            $this->load();
        }

        $data = $this->_content[$this->_dataSegment];

        return $data;
    }
}