<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);
$id = (int)($_GET['id'] ?? 0);
if ($id) {
  $st = $pdo->prepare("DELETE FROM agenda_kegiatan WHERE id=?");
  $st->execute([$id]);
}
header('Location: agenda_list.php'); exit;
?>
