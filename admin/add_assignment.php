<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$msg = ''; $ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $t = trim($_POST['title'] ?? ''); $d = trim($_POST['description'] ?? ''); $dl = $_POST['deadline'] ?? '';
    if ($t === '' || $d === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dl)) {
        $msg = 'Please fill all fields.';
    } else {
        $s = mysqli_prepare($conn, 'INSERT INTO assignments (title, description, deadline) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($s, 'sss', $t, $d, $dl);
        if (mysqli_stmt_execute($s)) { $ok = true; $msg = 'Assignment created.'; }
        else { $msg = 'Error creating assignment.'; }
    }
}
$title = 'Create Assignment'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","Assignments"=>"assignments.php","Submissions"=>"submissions.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>Create Assignment</h1>
<div class="card">
  <?php if ($msg): ?><div class="alert <?= $ok ? 'ok' : 'err' ?>"><?= e($msg) ?></div><?php endif; ?>
  <form method="POST">
    <?= csrf_field() ?>
    <label>Title</label><input type="text" name="title" placeholder="e.g. PHP CRUD Project" required>
    <label>Description</label><textarea name="description" required></textarea>
    <label>Deadline</label><input type="date" name="deadline" required>
    <div class="actions"><button class="btn">Create</button><a class="btn secondary" href="dashboard.php">Back</a></div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
