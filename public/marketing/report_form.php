<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['marketing']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_marketing.php';
$me = current_user();

$tugas_id = (int)($_GET['tugas_id'] ?? 0);
$st = $pdo->prepare("SELECT t.*, a.nama_kegiatan FROM tugas t
                     JOIN agenda_kegiatan a ON a.id=t.agenda_id
                     WHERE t.id=? AND t.assigned_to=?");
$st->execute([$tugas_id,$me['id']]);
$t = $st->fetch(PDO::FETCH_ASSOC);
if (!$t) { echo 'Tugas tidak ditemukan'; include __DIR__.'/../../includes/footer.php'; exit; }

$stL = $pdo->prepare("SELECT * FROM laporan_tugas WHERE tugas_id=?");
$stL->execute([$tugas_id]);
$lap = $stL->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $tgl = $_POST['tanggal_pelaksanaan'] ?? null;
  $lok = trim($_POST['lokasi_aktual'] ?? '');
  $dur = (int)($_POST['durasi_menit'] ?? 0);
  $pes = (int)($_POST['hasil_peserta'] ?? 0);
  $leads = (int)($_POST['hasil_leads'] ?? 0);
  $mat = trim($_POST['materi_dibagikan'] ?? '');
  $dok = trim($_POST['dokumentasi_link'] ?? '');
  $ken = trim($_POST['kendala'] ?? '');
  $rek = trim($_POST['rekomendasi'] ?? '');

  if ($lap) {
    $up = $pdo->prepare("UPDATE laporan_tugas SET 
      tanggal_pelaksanaan=?,lokasi_aktual=?,durasi_menit=?,hasil_peserta=?,hasil_leads=?,
      materi_dibagikan=?,dokumentasi_link=?,kendala=?,rekomendasi=?
      WHERE tugas_id=?");
    $up->execute([$tgl,$lok,$dur,$pes,$leads,$mat,$dok,$ken,$rek,$tugas_id]);
  } else {
    $ins = $pdo->prepare("INSERT INTO laporan_tugas
      (tugas_id,tanggal_pelaksanaan,lokasi_aktual,durasi_menit,hasil_peserta,hasil_leads,
       materi_dibagikan,dokumentasi_link,kendala,rekomendasi)
      VALUES (?,?,?,?,?,?,?,?,?,?)");
    $ins->execute([$tugas_id,$tgl,$lok,$dur,$pes,$leads,$mat,$dok,$ken,$rek]);
  }
  header('Location: task_view.php?id='.$tugas_id); exit;
}
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3">
    <h5>Form Laporan Tugas</h5>
    <p class="small text-muted">Agenda: <?php echo htmlspecialchars($t['nama_kegiatan']); ?> | Tugas: <?php echo htmlspecialchars($t['judul_tugas']); ?></p>
    <form method="post" class="row g-2">
      <div class="col-md-4">
        <label class="form-label">Tanggal Pelaksanaan</label>
        <input type="date" class="form-control" name="tanggal_pelaksanaan" value="<?php echo htmlspecialchars($lap['tanggal_pelaksanaan'] ?? ''); ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Lokasi Aktual</label>
        <input class="form-control" name="lokasi_aktual" value="<?php echo htmlspecialchars($lap['lokasi_aktual'] ?? ''); ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Durasi (menit)</label>
        <input type="number" class="form-control" name="durasi_menit" value="<?php echo htmlspecialchars($lap['durasi_menit'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Jumlah Peserta</label>
        <input type="number" class="form-control" name="hasil_peserta" value="<?php echo htmlspecialchars($lap['hasil_peserta'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Jumlah Leads</label>
        <input type="number" class="form-control" name="hasil_leads" value="<?php echo htmlspecialchars($lap['hasil_leads'] ?? ''); ?>">
      </div>
      <div class="col-md-12">
        <label class="form-label">Materi Dibagikan</label>
        <textarea class="form-control" rows="2" name="materi_dibagikan"><?php echo htmlspecialchars($lap['materi_dibagikan'] ?? ''); ?></textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label">Link Dokumentasi</label>
        <input class="form-control" type="url" name="dokumentasi_link" value="<?php echo htmlspecialchars($lap['dokumentasi_link'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kendala</label>
        <textarea class="form-control" rows="2" name="kendala"><?php echo htmlspecialchars($lap['kendala'] ?? ''); ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Rekomendasi</label>
        <textarea class="form-control" rows="2" name="rekomendasi"><?php echo htmlspecialchars($lap['rekomendasi'] ?? ''); ?></textarea>
      </div>
      <div class="col-12 d-flex justify-content-between mt-3">
        <a href="task_view.php?id=<?php echo $tugas_id; ?>" class="btn btn-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
