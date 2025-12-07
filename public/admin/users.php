<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['admin']);

include __DIR__.'/../../includes/header.php';
include __DIR__.'/../../includes/nav_admin.php';

// ================= SIMPAN =================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact'] ?? '');
    $unit = trim($_POST['unit'] ?? '');
    $role_id = (int)$_POST['role_id'];
    $password = $_POST['password'] ?? '';

    if ($id) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare(
                "UPDATE users SET name=?,email=?,contact=?,unit=?,role_id=?,password=? WHERE id=?"
            )->execute([$name,$email,$contact,$unit,$role_id,$hash,$id]);
        } else {
            $pdo->prepare(
                "UPDATE users SET name=?,email=?,contact=?,unit=?,role_id=? WHERE id=?"
            )->execute([$name,$email,$contact,$unit,$role_id,$id]);
        }
    } else {
        $hash = password_hash($password ?: '1234', PASSWORD_DEFAULT);
        $pdo->prepare(
            "INSERT INTO users(name,email,password,contact,unit,role_id)
             VALUES(?,?,?,?,?,?)"
        )->execute([$name,$email,$hash,$contact,$unit,$role_id]);
    }
    header("Location: users.php");
    exit;
}

// ================= HAPUS =================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== 1) { // admin utama tidak boleh dihapus
        $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    }
    header("Location: users.php");
    exit;
}

// ================= DATA =================
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("
    SELECT u.*, r.name AS role_name
    FROM users u 
    JOIN roles r ON r.id = u.role_id
    ORDER BY u.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-md-9 col-lg-10 p-4">

    <!-- FORM -->
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Form Anggota Tim</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="id" id="id">

                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input class="form-control" id="name" name="name" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" id="email" name="email" type="email" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kontak</label>
                    <input class="form-control" id="contact" name="contact">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Unit</label>
                    <input class="form-control" id="unit" name="unit">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="role_id" name="role_id">
                        <?php foreach($roles as $r): ?>
                            <option value="<?= $r['id'] ?>">
                                <?= htmlspecialchars($r['name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input class="form-control" id="password" name="password"
                           placeholder="Kosong = tidak diubah">
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-end">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow-sm">
        <div class="card-header fw-bold">Daftar Anggota Tim</div>
        <div class="table-responsive">
            <table class="table table-striped m-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kontak</th>
                        <th>Unit</th>
                        <th>Role</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['contact']) ?></td>
                        <td><?= htmlspecialchars($u['unit']) ?></td>
                        <td>
                            <span class="badge bg-secondary">
                                <?= htmlspecialchars($u['role_name']) ?>
                            </span>
                        </td>
                        <td class="d-flex gap-1">
                            <button class="btn btn-sm btn-warning"
                                onclick='editUser(<?= json_encode($u) ?>)'>
                                Edit
                            </button>

                            <?php if ($u['id'] != 1): ?>
                            <a href="users.php?delete=<?= $u['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin hapus user ini?')">
                               Hapus
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function editUser(u){
    id.value = u.id;
    name.value = u.name;
    email.value = u.email;
    contact.value = u.contact ?? '';
    unit.value = u.unit ?? '';
    role_id.value = u.role_id;
    password.value = '';
    window.scrollTo({top:0,behavior:'smooth'});
}
</script>

<?php include __DIR__.'/../../includes/footer.php'; ?>
