<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$id = (int)($_GET['id'] ?? 0);
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $m = filter_var($_POST['marks'] ?? '', FILTER_VALIDATE_INT);
    if ($m === false || $m < 0 || $m > 100) {
        $msg = 'Marks must be a whole number between 0 and 100.';
    } else {
        $u = mysqli_prepare($conn, 'UPDATE submissions SET marks = ? WHERE id = ?');
        mysqli_stmt_bind_param($u, 'ii', $m, $id);
        if (mysqli_stmt_execute($u)) { header('Location: submissions.php'); exit(); }
        $msg = 'Failed to update marks.';
    }
}
$s = mysqli_prepare($conn, 'SELECT s.*, u.username, a.title FROM submissions s
  JOIN users u ON s.student_id = u.id JOIN assignments a ON s.assignment_id = a.id WHERE s.id = ?');
mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s);
$sub = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
if (!$sub) { http_response_code(404); die('Submission not found.'); }
$title = 'Grade Submission'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","Assignments"=>"assignments.php","Submissions"=>"submissions.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>Grade Submission</h1>
<div class="card">
  <p><strong>Student:</strong> <?= e($sub['username']) ?><br><strong>Assignment:</strong> <?= e($sub['title']) ?><br>
  <a href="../uploads/<?= rawurlencode($sub['file_name']) ?>" target="_blank">View submitted file</a></p>
  <?php if ($msg): ?><div class="alert err"><?= e($msg) ?></div><?php endif; ?>
  <form method="POST">
    <?= csrf_field() ?>
    <label>Marks (0–100)</label>
    <input type="number" name="marks" min="0" max="100" value="<?= e($sub['marks'] ?? '') ?>" required>
    <div class="actions"><button class="btn">Save Marks</button><a class="btn secondary" href="submissions.php">Back</a></div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
