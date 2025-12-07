<?php
require_once __DIR__ . '/../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi';
    } else {

        $stmt = $pdo->prepare(
            "SELECT u.id, u.name, u.password, r.name AS role_name
             FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE u.email = ?"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // simpan session
            $_SESSION['user_id'] = $user['id'];

            // redirect sesuai role
            if ($user['role_name'] === 'admin') {
                header('Location: admin/dashboard.php');
            } elseif ($user['role_name'] === 'marketing') {
                header('Location: marketing/dashboard.php');
            } elseif ($user['role_name'] === 'manager') {
                header('Location: manager/dashboard.php');
            } else {
                $error = 'Role tidak dikenali';
            }

            exit;

        } else {
            $error = 'Email atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-4 mx-auto card p-4 shadow">

        <h4 class="text-center mb-3">Login</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="mb-2">
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Email"
                       required>
            </div>

            <div class="mb-3">
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Password"
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Masuk
            </button>
        </form>

    </div>
</div>

</body>
</html>
