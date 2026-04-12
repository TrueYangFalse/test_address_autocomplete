<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 5432),
        'name' => getenv('DB_NAME') ?: 'food_service',
        'user' => getenv('DB_USER') ?: 'postgres',
        'pass' => getenv('DB_PASS') ?: 'admin',
    ],
];
