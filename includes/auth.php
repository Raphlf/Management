<?php
require_once __DIR__.'/../config.php';

function current_user() {
    global $pdo;
    if (empty($_SESSION['user_id'])) return null;
    $st = $pdo->prepare("SELECT u.*, r.name AS role_name
                         FROM users u JOIN roles r ON r.id=u.role_id
                         WHERE u.id=?");
    $st->execute([$_SESSION['user_id']]);
    return $st->fetch(PDO::FETCH_ASSOC);
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: '.BASE_URL.'/index.php');
        exit;
    }
}

function require_role($roles) {
    require_login();
    $u = current_user();
    if (!$u || !in_array($u['role_name'], $roles)) {
        http_response_code(403);
        echo "Akses ditolak";
        exit;
    }
}
?>
