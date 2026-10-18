<?php
// Database settings. Set environment variables in production;
// defaults work for local XAMPP.
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
    'name' => getenv('DB_NAME') ?: 'assignment_db',
    'port' => (int)(getenv('DB_PORT') ?: 3306),
];
