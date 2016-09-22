<?php
$id = isset($_POST['id'])?$_POST['id']:'';
$title = isset($_POST['title'])?$_POST['title']:'';
$slug = isset($_POST['slug'])?$_POST['slug']:'';
if($title && $slug) {
    try {
        $file = dirname(dirname(__DIR__)).'/data/categories.json';
        $categories = file_get_contents($file);
        $categories = json_decode($categories, true);
        if($id) {
            $index = $id;
            $method = 'update';
        } else {
            $index = $categories['index'] + 1;
            $categories['index'] = $index;
            $method = 'add';
        }
        $categories['data'][$index] = array('title' => $title, 'slug' => $slug);
        if (!defined('JSON_PRETTY_PRINT')) {
            define('JSON_PRETTY_PRINT', 128);
        }
        file_put_contents($file, json_encode($categories, JSON_PRETTY_PRINT));
        echo json_encode(array(
            'method'=>$method,
            'error'=>false,
            'message'=>'save data success',
            'data'=>array('id'=>$index, 'title' => $title, 'slug' => $slug)
        ));
    } catch (Exception $e) {
        echo json_encode(array('error'=>true, 'message'=>$e->getMessage()));
    }
} else {
    echo json_encode(array('error'=>true, 'message'=>'invalid data'));
}