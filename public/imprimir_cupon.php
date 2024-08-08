<?php
// public/imprimir_cupon.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

if (!isset($_GET['id']) || !isset($_SESSION['generar_cupon']) || $_SESSION['generar_cupon'] !== true) {
    header("Location: beneficios.php");
    exit;
}

$id_beneficio = $_GET['id'];
$beneficio = obtenerBeneficio($id_beneficio);
$codigo_verificacion = generarCupon($id_beneficio);
$_SESSION['generar_cupon'] = false;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Cupón</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .cupon {
            border: 2px dashed #000;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        .cupon img {
            max-width: 100%;
            height: auto;
        }
        .no-print {
            margin-top: 20px;
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="cupon">
        <h2><?= htmlspecialchars($beneficio['nombre_empresa']) ?></h2>
        <img src="<?= "http://localhost/" . htmlspecialchars($beneficio['imagen']) ?>" alt="Imagen de la Empresa">
        <p><strong><?= htmlspecialchars($beneficio['descripcion']) ?></strong></p>
        <p><strong>Código de Verificación: <?= htmlspecialchars($codigo_verificacion) ?></strong></p>
    </div>
    <div style="margin-bottom: 40px;" class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Imprimir</button>
    </div>
</body>
</html>
