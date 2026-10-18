<?php
session_start();
require_once __DIR__ . '/../db.php';

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function require_role(string $role, string $root = '../'): void {
    if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== $role) {
        header("Location: {$root}login.php");
        exit();
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        die('Invalid request. Go back and try again.');
    }
}
