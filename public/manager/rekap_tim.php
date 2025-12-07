<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['manager']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_manager.php';

$sql = "SELECT u.id,u.name,u.unit,
        SUM(t.status='selesai') AS selesai,
        SUM(t.status<>'selesai') AS belum,
        SUM(t.status<>'selesai' AND t.deadline<CURDATE()) AS terlambat
        FROM users u
        LEFT JOIN tugas t ON t.assigned_to=u.id
        WHERE u.role_id=2
        GROUP BY u.id,u.name,u.unit
        ORDER BY u.name";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3">
    <h5>Rekap Tugas per Anggota Tim</h5>
    <table class="table table-sm table-striped">
      <thead><tr><th>Nama</th><th>Unit</th><th>Selesai</th><th>Belum</th><th>Terlambat</th></tr></thead>
      <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?php echo htmlspecialchars($r['name']); ?></td>
          <td><?php echo htmlspecialchars($r['unit']); ?></td>
          <td><?php echo (int)$r['selesai']; ?></td>
          <td><?php echo (int)$r['belum']; ?></td>
          <td><?php echo (int)$r['terlambat']; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
