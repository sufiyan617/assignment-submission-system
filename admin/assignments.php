<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$result = mysqli_query($conn, 'SELECT * FROM assignments ORDER BY deadline ASC');
$title = 'Assignments'; $root = '../';
$nav = ["Dashboard"=>"dashboard.php","Assignments"=>"assignments.php","Submissions"=>"submissions.php"];
include __DIR__ . '/../includes/header.php';
?>
<h1>All Assignments</h1>
<?php if (mysqli_num_rows($result) === 0): ?><div class="card muted">No assignments yet.</div><?php endif; ?>
<?php while ($r = mysqli_fetch_assoc($result)): ?>
<div class="card">
  <h3><?= e($r['title']) ?></h3>
  <p><?= nl2br(e($r['description'])) ?></p>
  <p class="muted">Deadline: <strong><?= e($r['deadline']) ?></strong></p>
  <div class="actions">
    <a class="btn secondary" href="edit_assignment.php?id=<?= (int)$r['id'] ?>">Edit</a>
    <form method="POST" action="delete_assignment.php" onsubmit="return confirm('Delete this assignment and all its submissions?');">
      <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
      <button class="btn danger">Delete</button>
    </form>
  </div>
</div>
<?php endwhile; ?>
<a class="btn secondary" href="add_assignment.php">+ New Assignment</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
