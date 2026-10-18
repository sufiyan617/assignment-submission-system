<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$result = mysqli_query($conn, 'SELECT s.id, a.title, u.username, s.file_name, s.submitted_at, s.marks
  FROM submissions s JOIN assignments a ON s.assignment_id = a.id JOIN users u ON s.student_id = u.id
  ORDER BY s.submitted_at DESC');
$title = 'Submissions'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","Assignments"=>"assignments.php","Submissions"=>"submissions.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>Student Submissions</h1>
<div class="table-scroll"><table>
<tr><th>Student</th><th>Assignment</th><th>File</th><th>Submitted</th><th>Marks</th></tr>
<?php if (mysqli_num_rows($result) === 0): ?><tr><td colspan="5" class="muted">No submissions yet.</td></tr><?php endif; ?>
<?php while ($r = mysqli_fetch_assoc($result)): ?>
<tr>
  <td><?= e($r['username']) ?></td>
  <td><?= e($r['title']) ?></td>
  <td><a href="../uploads/<?= rawurlencode($r['file_name']) ?>" target="_blank">View file</a></td>
  <td><?= e($r['submitted_at']) ?></td>
  <td>
    <?= $r['marks'] !== null ? '<span class="badge ok">' . (int)$r['marks'] . '/100</span>' : '<span class="badge wait">Not graded</span>' ?>
    <a href="grade.php?id=<?= (int)$r['id'] ?>">Grade</a>
  </td>
</tr>
<?php endwhile; ?>
</table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
