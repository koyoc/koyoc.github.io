<?php
// public/agregar_producto.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ProductoController.php';
$categorias = getCategorias();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria'];
    $imagen = $_FILES['imagen'];

    try {
        agregarProducto($nombre, $descripcion, $precio, $stock, $categoria_id, $imagen);
        header("Location: productos.php?mensaje=Producto agregado exitosamente");
    } catch (Exception $e) {
        header("Location: agregar_producto.php?error=" . urlencode($e->getMessage()));
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        #imagePreview {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            display: none;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Agregar Producto</h1>
        <form action="agregar_producto.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
            </div>
            <div class="form-group">
                <label for="stock">Cantidad en Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select class="form-control" id="categoria" name="categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#categoriaModal">Agregar nueva categoría</button>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen del Producto:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                <div id="imagePreview"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Agregar Producto</button>
        </form>
        <p><a href="productos.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Volver</a></p>
    </div>

    <!-- Modal para agregar nueva categoría -->
    <div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoriaModalLabel">Agregar Nueva Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="categoriaForm">
                        <div class="form-group">
                            <label for="nombre_categoria">Nombre de la Categoría:</label>
                            <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $("#imagen").change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')').show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        $('#categoriaForm').on('submit', function(e) {
            e.preventDefault();
            var nombre_categoria = $("#nombre_categoria").val();

            $.ajax({
                url: "procesar_agregar_categoria.php",
                method: "POST",
                data: { nombre_categoria: nombre_categoria },
                dataType: 'json',
                success: function(data) {
                    var newOption = $('<option></option>')
                        .attr("value", data.id)
                        .text(data.nombre);
                    $("#categoria").append(newOption).val(data.id);
                    $("#nombre_categoria").val(''); // Limpiar el campo de entrada
                    $("#categoriaModal").modal('hide');
                },
                error: function() {
                    alert("Hubo un error al agregar la categoría. Por favor, inténtalo nuevamente.");
                }
            });
        });
    </script>
</body>
</html>
