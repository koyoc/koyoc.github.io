<?php
// public/beneficios.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

$search = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = $_POST['search'];
    $beneficios = searchBeneficios($search);
} else {
    $beneficios = getBeneficios();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generar_cupon'])) {
    $_SESSION['generar_cupon'] = true;
    header("Location: imprimir_cupon.php?id=" . $_POST['id_beneficio']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Beneficios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table thead th {
            text-align: center;
        }
        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }
        .table tbody td.description {
            width: 40% !important;
            word-wrap: break-word;
            white-space: normal;
        }
        .form-inline {
            display: flex;
            flex-wrap: nowrap;
        }
        .btn-action {
            display: block;
            width: 100%;
            margin-bottom: 5px;
        }
        .btn-action:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Beneficios</h1>
        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
        <form action="beneficios.php" method="post" class="form-inline mb-3">
            <input type="text" class="form-control mr-sm-2" name="search" placeholder="Buscar por Nombre" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="agregar_beneficio.php" class="btn btn-primary ml-3">Agregar Beneficio</a>
        </form>
        <?php if (empty($beneficios)): ?>
            <div class="alert alert-danger" role="alert">
                No se encontraron beneficios con esa referencia.
            </div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre de la Empresa</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($beneficios as $beneficio): ?>
                    <tr>
                        <td><img src="<?= " http://localhost/" . htmlspecialchars($beneficio['imagen']) ?>" alt="Imagen de la Empresa"
                            style="width: 100px;"></td>
                        <td>
                            <?= htmlspecialchars($beneficio['nombre_empresa']) ?>
                        </td>
                        <td class="description">
                            <?= htmlspecialchars($beneficio['descripcion']) ?>
                        </td>
                        <td>
                            <a href="editar_beneficio.php?id=<?= $beneficio['id_beneficio'] ?>"
                                class="btn btn-warning btn-sm btn-action">Editar</a>
                            <button class="btn btn-danger btn-sm btn-action" data-toggle="modal" data-target="#confirmModal"
                                data-id="<?= $beneficio['id_beneficio'] ?>"
                                data-nombre="<?= htmlspecialchars($beneficio['nombre_empresa']) ?>">Eliminar</button>
                            <form method="post" action="beneficios.php" style="display:inline;" target="_blank">
                                <input type="hidden" name="id_beneficio" value="<?= $beneficio['id_beneficio'] ?>">
                                <button type="submit" name="generar_cupon" class="btn btn-info btn-sm btn-action">
                                    <i class="fas fa-print"></i> Imprimir Cupón
                                </button>
                            </form>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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
                    ¿Estás seguro de que deseas eliminar el beneficio <strong id="beneficioNombre"></strong>?
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
        $('#confirmModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');

            var modal = $(this);
            modal.find('#beneficioNombre').text(nombre);
            modal.find('#confirmarEliminar').data('id', id);
        });

        $('#confirmarEliminar').on('click', function () {
            var id = $(this).data('id');
            wizndow.location.href = 'eliminar_beneficio.php?id=' + id;
        });
    </script>
</body>
</html>
