<?php
// public/reglas_puntos.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ReglasPuntosController.php';

$reglas = obtenerReglasPuntos();

// Procesar la actualización de reglas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_regla = $_POST['id_regla'];
    $monto = $_POST['monto'];
    $puntos = $_POST['puntos'];

    if (actualizarReglaPuntos($id_regla, $monto, $puntos)) {
        // Recargar las reglas después de la actualización
        $reglas = obtenerReglasPuntos();
        $mensaje = "Regla actualizada correctamente.";
    } else {
        $mensaje = "Error al actualizar la regla.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reglas de Puntos</title>
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
            <h1>Reglas de Puntos</h1>
        </div>

        <?php if (isset($mensaje)) : ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Monto de Compra (Pesos)</th>
                    <th>Puntos Asignados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reglas as $regla) : ?>
                    <tr>
                        <td><?= htmlspecialchars($regla['monto']) ?></td>
                        <td><?= htmlspecialchars($regla['puntos']) ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editarReglaModal" data-id="<?= $regla['id_regla'] ?>" data-monto="<?= $regla['monto'] ?>" data-puntos="<?= $regla['puntos'] ?>">Editar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de edición de regla -->
    <div class="modal fade" id="editarReglaModal" tabindex="-1" aria-labelledby="editarReglaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarReglaModalLabel">Editar Regla de Puntos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="reglas_puntos.php">
                    <div class="modal-body">
                        <input type="hidden" name="id_regla" id="id_regla">
                        <div class="form-group">
                            <label for="monto">Monto de Compra (Pesos)</label>
                            <input type="number" class="form-control" id="monto" name="monto" required>
                        </div>
                        <div class="form-group">
                            <label for="puntos">Puntos Asignados</label>
                            <input type="number" class="form-control" id="puntos" name="puntos" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#editarReglaModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id_regla = button.data('id');
            var monto = button.data('monto');
            var puntos = button.data('puntos');

            var modal = $(this);
            modal.find('#id_regla').val(id_regla);
            modal.find('#monto').val(monto);
            modal.find('#puntos').val(puntos);
        });
    </script>
</body>

</html>
