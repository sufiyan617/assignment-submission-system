<?php
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['role'])) { header('Location: index.php'); exit(); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, username, password, role FROM users WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        $ok = false;
        if ($user) {
            if (password_get_info($user['password'])['algo']) {
                $ok = password_verify($password, $user['password']);
            } elseif (hash_equals($user['password'], $password)) {
                // legacy plaintext password: accept once, then upgrade to a hash
                $ok = true;
                $h = password_hash($password, PASSWORD_DEFAULT);
                $up = mysqli_prepare($conn, 'UPDATE users SET password = ? WHERE id = ?');
                mysqli_stmt_bind_param($up, 'si', $h, $user['id']);
                mysqli_stmt_execute($up);
            }
        }

        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header('Location: index.php');
            exit();
        }
        $error = 'Invalid username or password.';
    }
}

$title = 'Login'; $root = '';
include __DIR__ . '/includes/header.php';
?>
<div class="card login">
  <h1>📚 Assignment System</h1>
  <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
  <form method="POST">
    <label>Username</label>
    <input type="text" name="username" autofocus required>
    <label>Password</label>
    <input type="password" name="password" required>
    <div class="actions"><button class="btn" style="width:100%">Login</button></div>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
