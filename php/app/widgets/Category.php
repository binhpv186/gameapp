<?php
namespace app\widgets;

use base\Widget;
use app\models\Category as CategoryModel;

class Category extends Widget
{
    public function run()
    {
        $model = new CategoryModel();
        $data = $model->findAll();
        return $this->render('category', array('data'=>$data));
    }
}