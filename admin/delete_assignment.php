<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: assignments.php'); exit(); }
csrf_check();
$id = (int)($_POST['id'] ?? 0);

// remove uploaded files first (DB rows go via ON DELETE CASCADE)
$s = mysqli_prepare($conn, 'SELECT file_name FROM submissions WHERE assignment_id = ?');
mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s);
$files = mysqli_fetch_all(mysqli_stmt_get_result($s), MYSQLI_ASSOC);

$d = mysqli_prepare($conn, 'DELETE FROM assignments WHERE id = ?');
mysqli_stmt_bind_param($d, 'i', $id);
if (mysqli_stmt_execute($d)) {
    foreach ($files as $f) { @unlink(__DIR__ . '/../uploads/' . basename($f['file_name'])); }
}
header('Location: assignments.php');
exit();
