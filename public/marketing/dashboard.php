<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['marketing']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_marketing.php';
$me = current_user();

$st = $pdo->prepare("SELECT 
  SUM(status='belum_mulai') AS belum,
  SUM(status='in_progress') AS progress,
  SUM(status='selesai') AS selesai,
  COUNT(*) AS total
  FROM tugas WHERE assigned_to=?");
$st->execute([$me['id']]);
$stat = $st->fetch(PDO::FETCH_ASSOC);

$st2 = $pdo->prepare("SELECT t.*, a.nama_kegiatan FROM tugas t
                      JOIN agenda_kegiatan a ON a.id=t.agenda_id
                      WHERE t.assigned_to=?
                      ORDER BY t.deadline ASC LIMIT 5");
$st2->execute([$me['id']]);
$tugas = $st2->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="row g-3 mb-3">
    <div class="col-md-4"><div class="card p-3 shadow-sm"><h6>Belum Mulai</h6><h2><?php echo (int)$stat['belum']; ?></h2></div></div>
    <div class="col-md-4"><div class="card p-3 shadow-sm"><h6>In Progress</h6><h2><?php echo (int)$stat['progress']; ?></h2></div></div>
    <div class="col-md-4"><div class="card p-3 shadow-sm"><h6>Selesai</h6><h2><?php echo (int)$stat['selesai']; ?></h2></div></div>
  </div>
  <div class="card shadow-sm p-3">
    <h5>To-Do Terdekat</h5>
    <table class="table table-sm">
      <thead><tr><th>Agenda</th><th>Tugas</th><th>Deadline</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach($tugas as $t): ?>
        <tr>
          <td><?php echo htmlspecialchars($t['nama_kegiatan']); ?></td>
          <td><a href="task_view.php?id=<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['judul_tugas']); ?></a></td>
          <td><?php echo htmlspecialchars($t['deadline']); ?></td>
          <td><?php echo htmlspecialchars($t['status']); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <a href="tasks.php" class="btn btn-primary btn-sm">Lihat semua tugas</a>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
