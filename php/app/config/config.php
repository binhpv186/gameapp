<?php
return array(
    'router' => array(
        '/<slug:[A-Za-z0-9_-]*>/<id:\d+>' => 'GET,POST Index\\Index',
        '/contact' => 'Index\\Contact'
    )
);