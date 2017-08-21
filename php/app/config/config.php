<?php
return array(
    'router' => array(
        '/<slug:[A-Za-z0-9_-]*>' => 'GET,POST IndexController\\Index',
        '/<slug:[A-Za-z0-9_-]*>/<id:\d+>' => 'GET,POST Index\\Index',
        '/contact' => 'Index\\Contact'
    ),
    'controllers' => array(
        'defaultController' => 'index',
        'defaultAction' => 'index'
    )
);