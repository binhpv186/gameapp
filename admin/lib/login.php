<?php
include 'app.php';
if($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = new User();
    if ($user->login($username, $password)) {
        echo json_encode(array('id'=>$user->getLoginToken(), 'user'=>array('id'=>$username, 'role'=>'admin')));
    }
}