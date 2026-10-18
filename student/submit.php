<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('student');
$aid = (int)($_GET['id'] ?? 0);
$sid = (int)$_SESSION['user_id'];

$s = mysqli_prepare($conn, 'SELECT * FROM assignments WHERE id = ?');
mysqli_stmt_bind_param($s, 'i', $aid); mysqli_stmt_execute($s);
$a = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
if (!$a) { http_response_code(404); die('Assignment not found.'); }

$late = date('Y-m-d') > $a['deadline'];
$c = mysqli_prepare($conn, 'SELECT id FROM submissions WHERE assignment_id = ? AND student_id = ?');
mysqli_stmt_bind_param($c, 'ii', $aid, $sid); mysqli_stmt_execute($c);
$done = mysqli_num_rows(mysqli_stmt_get_result($c)) > 0;

$msg = ''; $ok = false;
const MAX_BYTES = 5 * 1024 * 1024;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$done && !$late) {
    csrf_check();
    $f = $_FILES['assignment_file'] ?? null;
    if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
        $msg = 'Please select a file (max 5 MB).';
    } elseif ($f['size'] > MAX_BYTES) {
        $msg = 'File is too large (max 5 MB).';
    } else {
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'doc', 'docx'], true)) {
            $msg = 'Only PDF, DOC and DOCX files are allowed.';
        } else {
            $safe = preg_replace('/[^A-Za-z0-9._-]+/', '_', pathinfo($f['name'], PATHINFO_FILENAME));
            $name = bin2hex(random_bytes(6)) . '_' . substr($safe, 0, 60) . '.' . $ext;
            $path = __DIR__ . '/../uploads/' . $name;
            if (move_uploaded_file($f['tmp_name'], $path)) {
                $i = mysqli_prepare($conn, 'INSERT INTO submissions (assignment_id, student_id, file_name) VALUES (?, ?, ?)');
                mysqli_stmt_bind_param($i, 'iis', $aid, $sid, $name);
                if (mysqli_stmt_execute($i)) { $ok = true; $done = true; $msg = 'Assignment submitted successfully!'; }
                else { @unlink($path); $msg = 'Could not save submission (maybe already submitted).'; }
            } else { $msg = 'File upload failed.'; }
        }
    }
}
$title = 'Submit Assignment'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","My Marks"=>"view_marks.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1><?= e($a['title']) ?></h1>
<div class="card">
  <p><?= nl2br(e($a['description'])) ?></p>
  <p class="muted">Deadline: <strong><?= e($a['deadline']) ?></strong></p>
  <?php if ($msg): ?><div class="alert <?= $ok ? 'ok' : 'err' ?>"><?= e($msg) ?></div><?php endif; ?>
  <?php if ($done): ?>
    <?php if (!$ok): ?><div class="alert err">You have already submitted this assignment.</div><?php endif; ?>
  <?php elseif ($late): ?>
    <div class="alert err">Submission deadline has passed.</div>
  <?php else: ?>
    <form method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <label>Assignment file (PDF, DOC, DOCX · max 5 MB)</label>
      <input type="file" name="assignment_file" accept=".pdf,.doc,.docx" required>
      <div class="actions"><button class="btn">Submit</button></div>
    </form>
  <?php endif; ?>
  <div class="actions"><a class="btn secondary" href="dashboard.php">← Back</a></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
