<?php
$id = isset($_POST['id'])?$_POST['id']:'';
$title = isset($_POST['title'])?$_POST['title']:'';
$slug = isset($_POST['slug'])?$_POST['slug']:'';
if($id && $title && $slug) {
    try {
        $file = dirname(dirname(__DIR__)).'/data/categories.json';
        $categories = file_get_contents($file);
        $categories = json_decode($categories);
        $categories[] = array('id' => $id, 'title' => $title, 'slug' => $slug);
        file_put_contents($file, json_encode($categories));
        echo json_encode(array('error'=>false, 'message'=>'save data success'));
    } catch (Exception $e) {
        echo json_encode(array('error'=>true, 'message'=>$e->getMessage()));
    }
} else {
    echo json_encode(array('error'=>true, 'message'=>'invalid data'));
}