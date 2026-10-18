<?php
// expects: $title, $root ("" or "../"), optional $nav (array label=>href)
$root = $root ?? '../';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Assignment System') ?> · Assignment System</title>
<link rel="stylesheet" href="<?= $root ?>assets/style.css">
</head>
<body>
<?php if (!empty($_SESSION['user_id'])): ?>
<header class="topbar">
  <span class="brand">📚 Assignment System</span>
  <nav>
    <?php foreach (($nav ?? []) as $label => $href): ?>
      <a href="<?= e($href) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="<?= $root ?>logout.php" class="logout">Logout (<?= e($_SESSION['username']) ?>)</a>
  </nav>
</header>
<?php endif; ?>
<main class="wrap">
