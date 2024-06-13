<?php
// Check if admin is logged in
if (isset($_SESSION['editor_id'])) {
    unset($_SESSION['editor_id']);
    unset($_SESSION['admin_id']);
    session_unset();
    session_destroy();
    header('Location: ' . URLROOT . '/editor/login');
    exit;
}
?>