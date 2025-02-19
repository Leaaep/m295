<?php

use App\Core\LoadEnv;

LoadEnv::load(base_path('.env'));

return [
    'database' => [
        'host' => 'localhost',
        'password' => 'root',
        'port' => '3306',
        'dbname' => 'kursverwaltung',
        'charset' => 'utf8mb4',
    ],
];