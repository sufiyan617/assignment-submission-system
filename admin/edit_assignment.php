<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$id = (int)($_GET['id'] ?? 0);
$s = mysqli_prepare($conn, 'SELECT * FROM assignments WHERE id = ?');
mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s);
$a = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
if (!$a) { http_response_code(404); die('Assignment not found.'); }

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $t = trim($_POST['title'] ?? ''); $d = trim($_POST['description'] ?? ''); $dl = $_POST['deadline'] ?? '';
    if ($t === '' || $d === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dl)) {
        $msg = 'All fields are required.';
    } else {
        $u = mysqli_prepare($conn, 'UPDATE assignments SET title = ?, description = ?, deadline = ? WHERE id = ?');
        mysqli_stmt_bind_param($u, 'sssi', $t, $d, $dl, $id);
        if (mysqli_stmt_execute($u)) { header('Location: assignments.php'); exit(); }
        $msg = 'Failed to update assignment.';
    }
}
$title = 'Edit Assignment'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","Assignments"=>"assignments.php","Submissions"=>"submissions.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>Edit Assignment</h1>
<div class="card">
  <?php if ($msg): ?><div class="alert err"><?= e($msg) ?></div><?php endif; ?>
  <form method="POST">
    <?= csrf_field() ?>
    <label>Title</label><input type="text" name="title" value="<?= e($a['title']) ?>" required>
    <label>Description</label><textarea name="description" required><?= e($a['description']) ?></textarea>
    <label>Deadline</label><input type="date" name="deadline" value="<?= e($a['deadline']) ?>" required>
    <div class="actions"><button class="btn">Update</button><a class="btn secondary" href="assignments.php">Back</a></div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
