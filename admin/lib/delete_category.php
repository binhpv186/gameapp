<?php
$id = isset($_POST['id'])?$_POST['id']:'';
if($id) {
    try {
        $file = dirname(dirname(__DIR__)).'/data/categories.json';
        $categories = file_get_contents($file);
        $categories = preg_replace('/\{"id":["]'.$id.'["][^\}]*\}/', '', $categories);
        $categories = str_replace(',,', ',', $categories);
        $categories = str_replace(',]', ']', $categories);
        $categories = str_replace('[,', '[', $categories);
        file_put_contents($file, $categories);
        echo json_encode(array('error'=>false, 'message'=>'save data success'));
    } catch (Exception $e) {
        echo json_encode(array('error'=>true, 'message'=>$e->getMessage()));
    }
} else {
    echo json_encode(array('error'=>true, 'message'=>'invalid data'));
}