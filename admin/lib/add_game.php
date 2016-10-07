<?php
$id = isset($_POST['id'])?$_POST['id']:'';
$title = isset($_POST['title'])?$_POST['title']:'';
$slug = isset($_POST['slug'])?$_POST['slug']:'';
$desc = isset($_POST['desc'])?$_POST['desc']:'';
$meta_title = isset($_POST['meta_title'])?$_POST['meta_title']:'';
$meta_desc = isset($_POST['meta_desc'])?$_POST['meta_desc']:'';
$category = isset($_POST['category'])?$_POST['category']:2;
if($title && $slug && $category) {
    $file = dirname(dirname(__DIR__)) . '/data/games.json';
    $content = file_get_contents($file);
    $games = json_decode($content, true);
    if($id) {
        $index = $id;
        $method = 'update';
    } else {
        $index = $games['index'] + 1;
        $games['index'] = $index;
        $method = 'add';
    }
    $games['data'][$index] = array(
        'title' => $title,
        'slug' => $slug,
        'category' => $category,
        'desc' => $desc,
        'meta_title' => $meta_title,
        'meta_desc' => $meta_desc
    );
    if (!defined('JSON_PRETTY_PRINT')) {
        define('JSON_PRETTY_PRINT', 128);
    }
    file_put_contents($file, json_encode($games, JSON_PRETTY_PRINT));
    echo json_encode(array(
        'method'=>$method,
        'error'=>false,
        'message'=>'save data success',
        'data'=>array(
            'id'=>$index, 'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'desc' => $desc,
            'meta_title' => $meta_title,
            'meta_desc' => $meta_desc
        )
    ));
} else {

}

