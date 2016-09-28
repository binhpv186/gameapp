<?php
namespace app\controllers;

use base\Controller;

class IndexController extends Controller
{
    public function IndexAction()
    {
//        echo __CLASS__ . '<br/>' . __METHOD__;
        $this->render('index/index', array('name'=>'Binh', 'class'=>'test'));
    }
}