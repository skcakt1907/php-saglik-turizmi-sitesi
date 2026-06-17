<?php
require_once __DIR__ . '/../inc/config.php';
$_SESSION = [];
session_destroy();
header('Location: ' . SITE_URL . '/' . ADMIN_SLUG . '/');
exit;
