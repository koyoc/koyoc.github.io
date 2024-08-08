<?php
// public/clientes.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/PremioController.php';
include '../src/controllers/ClienteController.php';

$canjes = obtenerCanjesAdmin();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial premios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-start align-items-center my-4">
            <a href="dashboard.php" class="btn btn-secondary mr-3">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            <h1>Historial de canjes</h1>
        </div>
        <table id="canjesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Premio</th>
                    <th>Fecha de canje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($canjes as $canje) : ?>
                    <tr>
                        <td><?= htmlspecialchars($canje['nombre'] . ' ' . $canje['apellidos']) ?></td>
                        <td><?= htmlspecialchars($canje['nombre_premio']) ?></td>
                        <td><?= htmlspecialchars($canje['fecha']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#canjesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>

</html>
