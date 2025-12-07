<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['marketing']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_marketing.php';
$me = current_user();

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare("SELECT t.*, a.nama_kegiatan FROM tugas t
                     JOIN agenda_kegiatan a ON a.id=t.agenda_id
                     WHERE t.id=? AND t.assigned_to=?");
$st->execute([$id,$me['id']]);
$t = $st->fetch(PDO::FETCH_ASSOC);
if (!$t) { echo 'Tugas tidak ditemukan'; include __DIR__.'/../../includes/footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $status = $_POST['status'] ?? $t['status'];
  $catatan = trim($_POST['catatan_lapangan'] ?? '');
  $waktu = $_POST['waktu_realisasi'] ?? null;
  $up = $pdo->prepare("UPDATE tugas SET status=?,catatan_lapangan=?,waktu_realisasi=? WHERE id=?");
  $up->execute([$status,$catatan,$waktu,$id]);
  header('Location: task_view.php?id='.$id); exit;
}

$stL = $pdo->prepare("SELECT * FROM laporan_tugas WHERE tugas_id=?");
$stL->execute([$id]);
$lap = $stL->fetch(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3 mb-3">
    <h5><?php echo htmlspecialchars($t['judul_tugas']); ?></h5>
    <p class="mb-1"><strong>Agenda:</strong> <?php echo htmlspecialchars($t['nama_kegiatan']); ?></p>
    <p class="mb-1"><strong>Deadline:</strong> <?php echo htmlspecialchars($t['deadline']); ?></p>
    <form method="post" class="row g-2 mt-2">
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
          <?php foreach(['belum_mulai','in_progress','selesai','tertunda','batal'] as $s): ?>
            <option value="<?php echo $s; ?>" <?php if($t['status']===$s) echo 'selected'; ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Waktu Realisasi</label>
        <input type="datetime-local" class="form-control" name="waktu_realisasi"
               value="<?php echo $t['waktu_realisasi'] ? date('Y-m-d\TH:i',strtotime($t['waktu_realisasi'])) : ''; ?>">
      </div>
      <div class="col-md-12">
        <label class="form-label">Catatan Lapangan</label>
        <textarea class="form-control" rows="3" name="catatan_lapangan"><?php echo htmlspecialchars($t['catatan_lapangan']); ?></textarea>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
  <div class="card shadow-sm p-3">
    <h5>Laporan Kegiatan</h5>
    <?php if($lap): ?>
      <p><strong>Tgl Pelaksanaan:</strong> <?php echo htmlspecialchars($lap['tanggal_pelaksanaan']); ?></p>
      <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($lap['lokasi_aktual']); ?></p>
      <p><strong>Durasi:</strong> <?php echo htmlspecialchars($lap['durasi_menit']); ?> menit</p>
      <p><strong>Peserta / Leads:</strong> <?php echo htmlspecialchars($lap['hasil_peserta']); ?> / <?php echo htmlspecialchars($lap['hasil_leads']); ?></p>
      <p><strong>Materi:</strong><br><?php echo nl2br(htmlspecialchars($lap['materi_dibagikan'])); ?></p>
      <p><strong>Dokumentasi:</strong> <?php if($lap['dokumentasi_link']): ?><a href="<?php echo htmlspecialchars($lap['dokumentasi_link']); ?>" target="_blank">Lihat</a><?php else: ?>-<?php endif; ?></p>
      <p><strong>Kendala:</strong><br><?php echo nl2br(htmlspecialchars($lap['kendala'])); ?></p>
      <p><strong>Rekomendasi:</strong><br><?php echo nl2br(htmlspecialchars($lap['rekomendasi'])); ?></p>
      <a class="btn btn-outline-primary btn-sm" href="report_form.php?tugas_id=<?php echo $id; ?>">Edit Laporan</a>
    <?php else: ?>
      <p class="text-muted">Belum ada laporan.</p>
      <a class="btn btn-primary btn-sm" href="report_form.php?tugas_id=<?php echo $id; ?>">Isi Laporan</a>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
