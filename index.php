<?php
session_start();
if (!empty($_SESSION['role'])) {
    header('Location: ' . ($_SESSION['role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
} else {
    header('Location: login.php');
}
exit();
