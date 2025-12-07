<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_admin.php';

$agenda = $pdo->query("SELECT a.*, k.nama AS kategori_nama
                       FROM agenda_kegiatan a
                       LEFT JOIN kategori_kegiatan k ON k.id=a.kategori_id
                       ORDER BY a.tanggal_mulai DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="col-md-9">
  <div class="d-flex justify-content-between mb-2">
    <h5>Agenda Kegiatan</h5>
    <a href="agenda_form.php" class="btn btn-primary btn-sm">+ Tambah Agenda</a>
  </div>
  <div class="card shadow-sm p-3">
    <table class="table table-sm table-striped">
      <thead>
        <tr>
          <th>Nama</th><th>Kategori</th><th>Lokasi/Mitra</th>
          <th>Periode</th><th>Target</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($agenda as $a): ?>
        <tr>
          <td><?php echo htmlspecialchars($a['nama_kegiatan']); ?></td>
          <td><?php echo htmlspecialchars($a['kategori_nama']); ?></td>
          <td><?php echo htmlspecialchars($a['lokasi_mitra']); ?></td>
          <td><?php echo htmlspecialchars($a['tanggal_mulai']).' s/d '.htmlspecialchars($a['tanggal_selesai']); ?></td>
          <td><?php echo 'Peserta: '.(int)$a['target_peserta'].' | Leads: '.(int)$a['target_leads']; ?></td>
          <td><?php echo htmlspecialchars($a['status_global']); ?></td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="agenda_form.php?id=<?php echo $a['id']; ?>">Edit</a>
            <a class="btn btn-sm btn-outline-primary" href="agenda_tasks.php?agenda_id=<?php echo $a['id']; ?>">Tugas</a>
            <a class="btn btn-sm btn-outline-danger" href="agenda_delete.php?id=<?php echo $a['id']; ?>" onclick="return confirm('Hapus agenda?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
