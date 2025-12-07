<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_admin.php';

$id = $_GET['id'] ?? '';
$agenda = null;
if ($id) {
  $st = $pdo->prepare("SELECT * FROM agenda_kegiatan WHERE id=?");
  $st->execute([$id]);
  $agenda = $st->fetch(PDO::FETCH_ASSOC);
}
$kategori = $pdo->query("SELECT * FROM kategori_kegiatan ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $id = $_POST['id'] ?? '';
  $nama = trim($_POST['nama_kegiatan'] ?? '');
  $kategori_id = $_POST['kategori_id'] ?: null;
  $lokasi = trim($_POST['lokasi_mitra'] ?? '');
  $mulai = $_POST['tanggal_mulai'] ?? '';
  $selesai = $_POST['tanggal_selesai'] ?? '';
  $target_peserta = (int)($_POST['target_peserta'] ?? 0);
  $target_leads = (int)($_POST['target_leads'] ?? 0);
  $deadline = $_POST['deadline'] ?? null;
  $status = $_POST['status_global'] ?? 'direncanakan';

  if (!$nama || !$mulai || !$selesai) $error='Nama dan tanggal wajib';
  elseif ($mulai > $selesai) $error='Tanggal mulai tidak boleh setelah tanggal selesai';
  else {
    if ($id) {
      $st = $pdo->prepare("UPDATE agenda_kegiatan SET 
          nama_kegiatan=?, kategori_id=?, lokasi_mitra=?, tanggal_mulai=?, tanggal_selesai=?,
          target_peserta=?, target_leads=?, deadline=?, status_global=?
          WHERE id=?");
      $st->execute([$nama,$kategori_id,$lokasi,$mulai,$selesai,$target_peserta,$target_leads,$deadline,$status,$id]);
    } else {
      $st = $pdo->prepare("INSERT INTO agenda_kegiatan
        (nama_kegiatan,kategori_id,lokasi_mitra,tanggal_mulai,tanggal_selesai,
         target_peserta,target_leads,deadline,status_global,created_by)
        VALUES (?,?,?,?,?,?,?,?,?,?)");
      $st->execute([$nama,$kategori_id,$lokasi,$mulai,$selesai,$target_peserta,$target_leads,$deadline,$status,current_user()['id']]);
    }
    header('Location: agenda_list.php'); exit;
  }
}
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3">
    <h5><?php echo $agenda?'Edit':'Tambah'; ?> Agenda</h5>
    <?php if($error): ?><div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post" class="row g-2">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($agenda['id'] ?? ''); ?>">
      <div class="col-md-6">
        <label class="form-label">Nama Kegiatan</label>
        <input class="form-control" name="nama_kegiatan" required value="<?php echo htmlspecialchars($agenda['nama_kegiatan'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <select class="form-select" name="kategori_id">
          <option value="">- Pilih -</option>
          <?php foreach($kategori as $k): ?>
            <option value="<?php echo $k['id']; ?>" <?php if(($agenda['kategori_id'] ?? '')==$k['id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($k['nama']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi / Mitra</label>
        <input class="form-control" name="lokasi_mitra" value="<?php echo htmlspecialchars($agenda['lokasi_mitra'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" class="form-control" name="tanggal_mulai" required value="<?php echo htmlspecialchars($agenda['tanggal_mulai'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" class="form-control" name="tanggal_selesai" required value="<?php echo htmlspecialchars($agenda['tanggal_selesai'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Target Peserta</label>
        <input type="number" class="form-control" name="target_peserta" value="<?php echo htmlspecialchars($agenda['target_peserta'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Target Leads</label>
        <input type="number" class="form-control" name="target_leads" value="<?php echo htmlspecialchars($agenda['target_leads'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Deadline</label>
        <input type="date" class="form-control" name="deadline" value="<?php echo htmlspecialchars($agenda['deadline'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status Global</label>
        <select class="form-select" name="status_global">
          <?php foreach(['direncanakan','berjalan','selesai','dibatalkan'] as $s): ?>
            <option value="<?php echo $s; ?>" <?php if(($agenda['status_global'] ?? 'direncanakan')==$s) echo 'selected'; ?>>
              <?php echo $s; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 d-flex justify-content-between mt-3">
        <a href="agenda_list.php" class="btn btn-secondary">Kembali</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
