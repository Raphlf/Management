<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['manager']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_manager.php';

$kategori_id = $_GET['kategori_id'] ?? '';
$lokasi = trim($_GET['lokasi'] ?? '');
$periode = $_GET['periode'] ?? ''; // yyyy-mm

$sql = "SELECT a.*, k.nama AS kategori_nama FROM agenda_kegiatan a
        LEFT JOIN kategori_kegiatan k ON k.id=a.kategori_id
        WHERE 1=1";
$params = [];
if ($kategori_id !== '') { $sql.=" AND a.kategori_id=:k"; $params[':k']=$kategori_id; }
if ($lokasi !== '') { $sql.=" AND a.lokasi_mitra LIKE :l"; $params[':l']="%$lokasi%"; }
if ($periode !== '') { $sql.=" AND DATE_FORMAT(a.tanggal_mulai,'%Y-%m')=:p"; $params[':p']=$periode; }
$sql.=" ORDER BY a.tanggal_mulai DESC";
$st = $pdo->prepare($sql); $st->execute($params);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
$kategori = $pdo->query("SELECT * FROM kategori_kegiatan ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3 mb-3">
    <h5>Monitoring Agenda</h5>
    <form class="row g-2" method="get">
      <div class="col-md-4">
        <label class="form-label">Periode (bulan)</label>
        <input type="month" class="form-control" name="periode" value="<?php echo htmlspecialchars($periode); ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <select class="form-select" name="kategori_id">
          <option value="">- Semua -</option>
          <?php foreach($kategori as $k): ?>
            <option value="<?php echo $k['id']; ?>" <?php if($kategori_id==$k['id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($k['nama']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Lokasi / Mitra</label>
        <input class="form-control" name="lokasi" value="<?php echo htmlspecialchars($lokasi); ?>">
      </div>
      <div class="col-12 d-flex justify-content-end mt-2">
        <button class="btn btn-primary me-2">Filter</button>
        <a href="monitor_agenda.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>
  </div>
  <div class="card shadow-sm p-3">
    <table class="table table-sm table-striped">
      <thead><tr><th>Nama</th><th>Kategori</th><th>Lokasi</th><th>Periode</th><th>Status</th><th>Target</th></tr></thead>
      <tbody>
      <?php foreach($rows as $a): ?>
        <tr>
          <td><?php echo htmlspecialchars($a['nama_kegiatan']); ?></td>
          <td><?php echo htmlspecialchars($a['kategori_nama']); ?></td>
          <td><?php echo htmlspecialchars($a['lokasi_mitra']); ?></td>
          <td><?php echo htmlspecialchars($a['tanggal_mulai']).' s/d '.htmlspecialchars($a['tanggal_selesai']); ?></td>
          <td><?php echo htmlspecialchars($a['status_global']); ?></td>
          <td><?php echo 'Peserta: '.(int)$a['target_peserta'].' | Leads: '.(int)$a['target_leads']; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
