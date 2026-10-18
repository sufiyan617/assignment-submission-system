<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('student');
$sid = (int)$_SESSION['user_id'];
$s = mysqli_prepare($conn, 'SELECT a.*, s.id AS submission_id, s.marks FROM assignments a
  LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ? ORDER BY a.deadline ASC');
mysqli_stmt_bind_param($s, 'i', $sid); mysqli_stmt_execute($s);
$result = mysqli_stmt_get_result($s);
$today = date('Y-m-d');
$title = 'Student Dashboard'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","My Marks"=>"view_marks.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>Available Assignments</h1>
<?php if (mysqli_num_rows($result) === 0): ?><div class="card muted">No assignments available.</div><?php endif; ?>
<?php while ($r = mysqli_fetch_assoc($result)): ?>
<div class="card">
  <h3><?= e($r['title']) ?></h3>
  <p><?= nl2br(e($r['description'])) ?></p>
  <p class="muted">Deadline: <strong><?= e($r['deadline']) ?></strong></p>
  <?php if ($r['submission_id']): ?>
    <span class="badge ok">✓ Submitted</span>
    <?= $r['marks'] !== null ? '<strong>' . (int)$r['marks'] . '/100</strong>' : '<span class="badge wait">Awaiting grading</span>' ?>
  <?php elseif ($today > $r['deadline']): ?>
    <span class="badge late">Deadline passed</span>
  <?php else: ?>
    <a class="btn" href="submit.php?id=<?= (int)$r['id'] ?>">Submit Assignment</a>
  <?php endif; ?>
</div>
<?php endwhile; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
