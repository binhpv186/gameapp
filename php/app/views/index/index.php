<?php
echo __FILE__;
echo '<br/>'.$name;
//echo '<br/>'.App::$app->request->getHeaders();
var_dump(App::$app->request->getServerParams());
var_dump($_REQUEST);