<?php
// public/eliminar_cupon.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

if (isset($_GET['codigo'])) {
    $codigo_verificacion = $_GET['codigo'];
    eliminarCupon($codigo_verificacion);
    header("Location: historial_cupones.php");
    exit;
} else {
    header("Location: historial_cupones.php");
    exit;
}
?>
