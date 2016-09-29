<?php
return array(
    'router' => array(
        '/<slug:\w+>/<id:\d+>' => 'index\\index',
        '/<controller>' => 'index\\hehe'
    )
);