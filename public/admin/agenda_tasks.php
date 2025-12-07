<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_admin.php';

$agenda_id = (int)($_GET['agenda_id'] ?? 0);
$st = $pdo->prepare("SELECT * FROM agenda_kegiatan WHERE id=?");
$st->execute([$agenda_id]);
$agenda = $st->fetch(PDO::FETCH_ASSOC);
if (!$agenda) { echo 'Agenda tidak ditemukan'; include __DIR__.'/../../includes/footer.php'; exit; }

$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $judul = trim($_POST['judul_tugas'] ?? '');
  $assigned = (int)($_POST['assigned_to'] ?? 0);
  $deadline = $_POST['deadline'] ?? null;
  $desk = trim($_POST['deskripsi'] ?? '');
  if (!$judul || !$assigned) $err='Judul & PIC wajib';
  else {
    // cek bentrok jadwal: user sudah tugas di agenda lain yg tanggalnya overlap
    $q = $pdo->prepare("SELECT a.nama_kegiatan FROM tugas t
                        JOIN agenda_kegiatan a ON a.id=t.agenda_id
                        WHERE t.assigned_to=? AND a.id<>? 
                          AND NOT (a.tanggal_selesai < ? OR a.tanggal_mulai > ?)");
    $q->execute([$assigned,$agenda_id,$agenda['tanggal_mulai'],$agenda['tanggal_selesai']]);
    $conf = $q->fetch(PDO::FETCH_ASSOC);
    if ($conf) $err='Jadwal bentrok dengan agenda: '.$conf['nama_kegiatan'];
    else {
      $ins = $pdo->prepare("INSERT INTO tugas(agenda_id,judul_tugas,assigned_to,deskripsi,deadline,status)
                            VALUES (?,?,?,?,?,'belum_mulai')");
      $ins->execute([$agenda_id,$judul,$assigned,$desk,$deadline]);
      header('Location: agenda_tasks.php?agenda_id='.$agenda_id); exit;
    }
  }
}

$users = $pdo->query("SELECT * FROM users WHERE role_id=2 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$st2 = $pdo->prepare("SELECT t.*, u.name AS pic FROM tugas t JOIN users u ON u.id=t.assigned_to WHERE t.agenda_id=? ORDER BY t.deadline");
$st2->execute([$agenda_id]);
$tugas = $st2->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3 mb-3">
    <h5>Tugas untuk Agenda: <?php echo htmlspecialchars($agenda['nama_kegiatan']); ?></h5>
    <p class="small text-muted">Periode: <?php echo htmlspecialchars($agenda['tanggal_mulai']).' s/d '.htmlspecialchars($agenda['tanggal_selesai']); ?></p>
    <?php if($err): ?><div class="alert alert-danger py-2"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post" class="row g-2">
      <div class="col-md-5">
        <label class="form-label">Judul Tugas</label>
        <input class="form-control" name="judul_tugas" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">PIC</label>
        <select class="form-select" name="assigned_to" required>
          <option value="">- Pilih -</option>
          <?php foreach($users as $u): ?>
            <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Deadline</label>
        <input type="date" class="form-control" name="deadline">
      </div>
      <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" rows="2" name="deskripsi"></textarea>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary">Tambah Tugas</button>
      </div>
    </form>
  </div>
  <div class="card shadow-sm p-3">
    <h6>Daftar Tugas</h6>
    <table class="table table-sm table-striped">
      <thead><tr><th>Judul</th><th>PIC</th><th>Deadline</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach($tugas as $t): ?>
        <tr>
          <td><?php echo htmlspecialchars($t['judul_tugas']); ?></td>
          <td><?php echo htmlspecialchars($t['pic']); ?></td>
          <td><?php echo htmlspecialchars($t['deadline']); ?></td>
          <td><?php echo htmlspecialchars($t['status']); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <a href="agenda_list.php" class="btn btn-secondary btn-sm mt-2">Kembali</a>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
