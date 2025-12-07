<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_admin.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id = $_POST['id'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    if ($nama) {
        if ($id) {
            $st = $pdo->prepare("UPDATE kategori_kegiatan SET nama=? WHERE id=?");
            $st->execute([$nama,$id]);
        } else {
            $st = $pdo->prepare("INSERT INTO kategori_kegiatan(nama) VALUES (?)");
            $st->execute([$nama]);
        }
    }
    header('Location: categories.php'); exit;
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $st = $pdo->prepare("DELETE FROM kategori_kegiatan WHERE id=?");
    $st->execute([$id]);
    header('Location: categories.php'); exit;
}
$data = $pdo->query("SELECT * FROM kategori_kegiatan ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="card shadow-sm p-3 mb-3">
    <h5>Kategori Kegiatan</h5>
    <form method="post" class="row g-2">
      <input type="hidden" name="id" id="id">
      <div class="col-md-8">
        <label class="form-label">Nama Kategori</label>
        <input class="form-control" name="nama" id="nama" required>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary w-100">Simpan</button>
      </div>
    </form>
  </div>
  <div class="card shadow-sm p-3">
    <table class="table table-sm">
      <thead><tr><th>ID</th><th>Nama</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($data as $k): ?>
        <tr>
          <td><?php echo $k['id']; ?></td>
          <td><?php echo htmlspecialchars($k['nama']); ?></td>
          <td>
            <button class="btn btn-sm btn-outline-secondary" onclick='editKategori(<?php echo json_encode($k); ?>)'>Edit</button>
            <a class="btn btn-sm btn-outline-danger" href="categories.php?delete=<?php echo $k['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function editKategori(k){
  id.value = k.id;
  nama.value = k.nama;
  window.scrollTo({top:0,behavior:'smooth'});
}
</script>
<?php include __DIR__.'/../../includes/footer.php'; ?>
