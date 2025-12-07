<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['marketing']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_marketing.php';
$me = current_user();

$st = $pdo->prepare("SELECT t.*, a.nama_kegiatan, l.id AS laporan_id
                     FROM tugas t
                     JOIN agenda_kegiatan a ON a.id=t.agenda_id
                     LEFT JOIN laporan_tugas l ON l.tugas_id=t.id
                     WHERE t.assigned_to=?
                     ORDER BY t.created_at DESC");
$st->execute([$me['id']]);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3">
    <h5>Riwayat Tugas</h5>
    <table class="table table-sm table-striped">
      <thead><tr><th>Agenda</th><th>Judul</th><th>Status</th><th>Deadline</th><th>Laporan</th></tr></thead>
      <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?php echo htmlspecialchars($r['nama_kegiatan']); ?></td>
          <td><?php echo htmlspecialchars($r['judul_tugas']); ?></td>
          <td><?php echo htmlspecialchars($r['status']); ?></td>
          <td><?php echo htmlspecialchars($r['deadline']); ?></td>
          <td><?php echo $r['laporan_id'] ? 'Sudah' : 'Belum'; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
