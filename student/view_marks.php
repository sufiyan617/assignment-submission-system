<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('student');
$sid = (int)$_SESSION['user_id'];
$s = mysqli_prepare($conn, 'SELECT a.title, s.submitted_at, s.marks FROM submissions s
  JOIN assignments a ON s.assignment_id = a.id WHERE s.student_id = ? ORDER BY s.submitted_at DESC');
mysqli_stmt_bind_param($s, 'i', $sid); mysqli_stmt_execute($s);
$result = mysqli_stmt_get_result($s);
$title = 'My Marks'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","My Marks"=>"view_marks.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>My Marks</h1>
<?php if (mysqli_num_rows($result) === 0): ?>
  <div class="card muted">You have not submitted any assignments yet.</div>
<?php else: ?>
<div class="table-scroll"><table>
<tr><th>Assignment</th><th>Submitted</th><th>Marks</th></tr>
<?php while ($r = mysqli_fetch_assoc($result)): ?>
<tr><td><?= e($r['title']) ?></td><td><?= e($r['submitted_at']) ?></td>
<td><?= $r['marks'] !== null ? '<span class="badge ok">' . (int)$r['marks'] . '/100</span>' : '<span class="badge wait">Not graded</span>' ?></td></tr>
<?php endwhile; ?>
</table></div>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
