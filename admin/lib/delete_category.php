<?php
$id = isset($_POST['id'])?$_POST['id']:'';
if($id) {
    try {
        $file = dirname(dirname(__DIR__)).'/data/categories.json';
        $content = file_get_contents($file);
        $categories = json_decode($content, true);
        unset($categories['data'][$id]);
        if (!defined('JSON_PRETTY_PRINT')) {
            define('JSON_PRETTY_PRINT', 128);
        }
        file_put_contents($file, json_encode($categories, JSON_PRETTY_PRINT));
        echo json_encode(array('error'=>false, 'message'=>'save data success'));
    } catch (Exception $e) {
        echo json_encode(array('error'=>true, 'message'=>$e->getMessage()));
    }
} else {
    echo json_encode(array('error'=>true, 'message'=>'invalid data'));
}