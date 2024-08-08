<?php
// public/eliminar_beneficio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

if (isset($_GET['id'])) {
    $id_beneficio = $_GET['id'];
    eliminarBeneficio($id_beneficio);
    header("Location: beneficios.php");
    exit;
} else {
    header("Location: beneficios.php");
    exit;
}
?>
