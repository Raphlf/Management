<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['manager']);
include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_manager.php';

$totalAgenda = $pdo->query("SELECT COUNT(*) FROM agenda_kegiatan")->fetchColumn();
$totalSelesai = $pdo->query("SELECT COUNT(*) FROM agenda_kegiatan WHERE status_global='selesai'")->fetchColumn();
$totalPeserta = $pdo->query("SELECT COALESCE(SUM(hasil_peserta),0) FROM laporan_tugas")->fetchColumn();
$totalLeads = $pdo->query("SELECT COALESCE(SUM(hasil_leads),0) FROM laporan_tugas")->fetchColumn();
?>
<div class="col-md-9">
  <div class="row g-3">
    <div class="col-md-3"><div class="card p-3 shadow-sm"><h6>Agenda</h6><h3><?php echo $totalAgenda; ?></h3></div></div>
    <div class="col-md-3"><div class="card p-3 shadow-sm"><h6>Agenda Selesai</h6><h3><?php echo $totalSelesai; ?></h3></div></div>
    <div class="col-md-3"><div class="card p-3 shadow-sm"><h6>Total Peserta</h6><h3><?php echo $totalPeserta; ?></h3></div></div>
    <div class="col-md-3"><div class="card p-3 shadow-sm"><h6>Total Leads</h6><h3><?php echo $totalLeads; ?></h3></div></div>
  </div>
</div>
<?php include __DIR__.'/../../includes/footer.php'; ?>
