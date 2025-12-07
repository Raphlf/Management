<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_admin.php';

$totalAgenda = $pdo->query("SELECT COUNT(*) FROM agenda_kegiatan")->fetchColumn();
$totalTugas  = $pdo->query("SELECT COUNT(*) FROM tugas")->fetchColumn();
$totalMarketing = $pdo->query("SELECT COUNT(*) FROM users WHERE role_id=2")->fetchColumn();
?>
<div class="col-md-9">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <h6 class="text-muted">Agenda</h6>
        <h2><?php echo $totalAgenda; ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <h6 class="text-muted">Tugas</h6>
        <h2><?php echo $totalTugas; ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <h6 class="text-muted">Tim Marketing</h6>
        <h2><?php echo $totalMarketing; ?></h2>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
