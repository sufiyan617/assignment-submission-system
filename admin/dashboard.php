<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$counts = [
  'Assignments' => mysqli_fetch_row(mysqli_query($conn, 'SELECT COUNT(*) FROM assignments'))[0],
  'Submissions' => mysqli_fetch_row(mysqli_query($conn, 'SELECT COUNT(*) FROM submissions'))[0],
  'Awaiting grading' => mysqli_fetch_row(mysqli_query($conn, 'SELECT COUNT(*) FROM submissions WHERE marks IS NULL'))[0],
];
$title = 'Admin Dashboard'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","Assignments"=>"assignments.php","Submissions"=>"submissions.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome, <?= e($_SESSION['username']) ?></h1>
<div class="grid">
<?php foreach ($counts as $label => $n): ?>
  <div class="card"><div class="muted"><?= e($label) ?></div><div class="stat"><?= (int)$n ?></div></div>
<?php endforeach; ?>
</div>
<div class="card">
  <h3>Assignment Management</h3>
  <p class="muted">Create and manage assignments for students.</p>
  <div class="actions"><a class="btn" href="add_assignment.php">Create Assignment</a><a class="btn secondary" href="assignments.php">View Assignments</a></div>
</div>
<div class="card">
  <h3>Submission Management</h3>
  <p class="muted">View student submissions and give marks.</p>
  <div class="actions"><a class="btn" href="submissions.php">View Submissions</a></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
