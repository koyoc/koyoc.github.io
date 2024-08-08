<?php
// public/eliminar_premio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/PremioController.php';

if (isset($_GET['id'])) {
    $id_premio = $_GET['id'];
    eliminarPremio($id_premio);
    header("Location: premios.php");
    exit;
} else {
    header("Location: premios.php");
    exit;
}
?>
