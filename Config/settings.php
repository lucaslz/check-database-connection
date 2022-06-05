<?php

return [
    'databases' => [
        'mysql-proxy' => [
            'host' => '127.0.0.1',
            'port' => '3366',
            'user' => 'root',
            'pass' => 'sqlmestre',
            'database' => 'sakila',
            'driver' => 'mysql'
        ],
        'mysql-master' => [
            'host' => '127.0.0.1',
            'port' => '3367',
            'user' => 'root',
            'pass' => 'sqlmestre',
            'database' => 'sakila',
            'driver' => 'mysql',
            'privilegied' => true
        ]
    ]
];