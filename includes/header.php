<?php
require_once __DIR__.'/auth.php';
$me = current_user();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Marketing Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo BASE_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="<?php echo BASE_URL; ?>/dashboard.php">MARVIS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navmain">
      <?php if($me): ?>
        <span class="me-3 small text-muted"><?php echo htmlspecialchars($me['name']); ?> (<?php echo htmlspecialchars($me['role_name']); ?>)</span>
        <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container-fluid my-3">
  <div class="row">
