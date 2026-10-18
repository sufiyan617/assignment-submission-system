<?php
$cfg = require __DIR__ . '/config.php';
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
if (!$conn) {
    http_response_code(500);
    die('Database connection failed. Check config.php / environment variables.');
}
mysqli_set_charset($conn, 'utf8mb4');
