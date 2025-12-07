<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['marketing']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_marketing.php';
$me = current_user();

$status = $_GET['status'] ?? '';
$deadline = $_GET['deadline'] ?? '';

$sql = "SELECT t.*, a.nama_kegiatan FROM tugas t
        JOIN agenda_kegiatan a ON a.id=t.agenda_id
        WHERE t.assigned_to=:uid";
$params = [':uid'=>$me['id']];
if ($status !== '') { $sql.=" AND t.status=:s"; $params[':s']=$status; }
if ($deadline !== '') { $sql.=" AND t.deadline<=:d"; $params[':d']=$deadline; }
$sql.=" ORDER BY t.deadline ASC";
$st = $pdo->prepare($sql); $st->execute($params);
$tugas = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3 mb-3">
    <h5>Tugas Saya</h5>
    <form class="row g-2" method="get">
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">- Semua -</option>
          <?php foreach(['belum_mulai','in_progress','selesai','tertunda','batal'] as $s): ?>
            <option value="<?php echo $s; ?>" <?php if($status===$s) echo 'selected'; ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Deadline sebelum</label>
        <input type="date" class="form-control" name="deadline" value="<?php echo htmlspecialchars($deadline); ?>">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary me-2">Filter</button>
        <a href="tasks.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>
  </div>
  <div class="card shadow-sm p-3">
    <table class="table table-sm table-striped">
      <thead><tr><th>Agenda</th><th>Judul</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($tugas as $t): ?>
        <tr>
          <td><?php echo htmlspecialchars($t['nama_kegiatan']); ?></td>
          <td><?php echo htmlspecialchars($t['judul_tugas']); ?></td>
          <td><?php echo htmlspecialchars($t['deadline']); ?></td>
          <td><?php echo htmlspecialchars($t['status']); ?></td>
          <td><a class="btn btn-sm btn-outline-primary" href="task_view.php?id=<?php echo $t['id']; ?>">Detail</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
