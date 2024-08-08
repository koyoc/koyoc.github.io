<?php
// public/historial_cupones.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

$cupones = getCupones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cupones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .codigo-verificacion {
            font-family: monospace;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Historial de Cupones Generados</h1>
        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre de la Empresa</th>
                    <th>Código de Verificación</th>
                    <th>Fecha de Creación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cupones as $index => $cupon): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($cupon['nombre_empresa']) ?></td>
                    <td>
                        <span class="codigo-verificacion" data-codigo="<?= htmlspecialchars($cupon['codigo_verificacion']) ?>">****</span>
                        <button class="btn btn-link toggle-codigo"><i class="fas fa-eye"></i></button>
                    </td>
                    <td><?= htmlspecialchars($cupon['fecha_generado']) ?></td>
                    <td><?= $cupon['usado'] ? 'Canjeado' : 'No Canjeado' ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmModal" data-codigo="<?= htmlspecialchars($cupon['codigo_verificacion']) ?>"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el cupón con código <strong id="codigoCupon"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var codigoActual;

            $('.toggle-codigo').on('click', function() {
                var $codigoSpan = $(this).siblings('.codigo-verificacion');
                var codigo = $codigoSpan.data('codigo');

                if ($codigoSpan.text() === '****') {
                    $codigoSpan.text(codigo);
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                    var $this = $(this);
                    setTimeout(function() {
                        $codigoSpan.text('****');
                        $this.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                    }, 10000);
                } else {
                    $codigoSpan.text('****');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            $('#confirmModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var codigo = button.data('codigo');
                var modal = $(this);
                modal.find('#codigoCupon').text(codigo);
                codigoActual = codigo;
            });

            $('#confirmarEliminar').on('click', function() {
                window.location.href = 'eliminar_cupon.php?codigo=' + codigoActual;
            });
        });
    </script>
</body>
</html>
