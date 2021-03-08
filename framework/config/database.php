<?php
return [
    'mysql' => [
        'type'   => 'mysql',
        'user'   => 'root',
        'pwd'    => '123abc',
        'host'   => '192.168.92.132',
        'port'   => '3306',
        'db'     => 'basic',
        'prefix' => 'basic2_',
    ],
    'redis' => [
        'host'      => '127.0.0.1',
        'port'      => '6379',
        'avalanche' => 3600,    //防止缓存雪崩,过期时间 = 所设置的过期的时间 + 0 到3600中的随机数
        'penetrate' => 60,      //防止缓存穿透，设置过期时间为 60秒
    ],
    'kafka' => [
        'host' => '127.0.0.1',
        'port' => '9092',
    ]
];