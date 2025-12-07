<?php
require_once __DIR__.'/../includes/auth.php';
require_login();
$u = current_user();
if ($u['role_name']==='admin') {
    header('Location: '.BASE_URL.'/admin/dashboard.php'); exit;
}
if ($u['role_name']==='marketing') {
    header('Location: '.BASE_URL.'/marketing/dashboard.php'); exit;
}
if ($u['role_name']==='manager') {
    header('Location: '.BASE_URL.'/manager/dashboard.php'); exit;
}
echo 'Role tidak dikenal';
?>
