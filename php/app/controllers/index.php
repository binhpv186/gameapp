<?php
namespace app\controllers;

use App;
use base\Config;
use base\Controller;

class Index extends Controller
{
    public function IndexAction()
    {
//        echo __CLASS__ . '<br/>' . __METHOD__;
//        var_dump(Config::get('router'));
//        var_dump(App::$app->getRouter());
        $this->render('index/index', array('name'=>'Binh', 'class'=>'test'));
    }
}