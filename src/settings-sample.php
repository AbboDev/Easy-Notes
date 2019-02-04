<?php
return [
    'settings' => [
        'displayErrorDetails'    => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'view_path' => __DIR__ . '/views/',
        ],

        // Monolog settings
        'logger' => [
            'name'  => 'slim-app',
            'path'  => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // PDO settings
        'db' => [
            'host'   => "host",
            'dbname' => "dbname",
            'user'   => "user",
            'pass'   => "pass",
        ],
    ],
];
