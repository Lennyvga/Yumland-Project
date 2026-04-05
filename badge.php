<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

// Fonctionnalité disponible en phase 3
header('Location: admin.php');
exit();
?>
