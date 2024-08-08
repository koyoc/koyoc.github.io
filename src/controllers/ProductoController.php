<?php
// src/controllers/ProductoController.php
include __DIR__ . '/../config/config.php';

function agregarProducto($nombre, $descripcion, $precio, $stock, $imagen, $id_categoria) {
    global $conn;

    // Guardar la imagen
    $target_dir = "../public/images/productos/";
    $target_file = $target_dir . basename($imagen["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($imagen["tmp_name"]);

    if ($check !== false) {
        if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
            // Insertar producto en la base de datos
            $stmt = $conn->prepare("INSERT INTO Productos (nombre, descripcion, precio, stock, imagen, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio, $stock, 'images/productos/' . basename($imagen["name"]), $id_categoria]);
            return true;
        } else {
            throw new Exception("Hubo un error al subir la imagen.");
        }
    } else {
        throw new Exception("El archivo no es una imagen.");
    }
}

function getCategorias() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Categorias");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarCategoria($nombre_categoria) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Categorias (nombre) VALUES (?)");
    $stmt->execute([$nombre_categoria]);
}

function getProductos() {
    global $conn;
    $stmt = $conn->query("SELECT p.*, c.nombre AS nombre_categoria FROM Productos p JOIN Categorias c ON p.id_categoria = c.id_categoria");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function eliminarProducto($id_producto) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Productos WHERE id_producto = ?");
    $stmt->execute([$id_producto]);
}

function obtenerProducto($id_producto) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
    $stmt->execute([$id_producto]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function actualizarProducto($id_producto, $nombre, $descripcion, $precio, $stock, $imagen, $id_categoria) {
    global $conn;

    if ($imagen) {
        // Guardar la imagen
        $target_dir = "../public/images/productos/";
        $target_file = $target_dir . basename($imagen["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($imagen["tmp_name"]);

        if ($check !== false) {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                // Actualizar producto en la base de datos con la nueva imagen
                $stmt = $conn->prepare("UPDATE Productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ?, id_categoria = ? WHERE id_producto = ?");
                $stmt->execute([$nombre, $descripcion, $precio, $stock, 'images/productos/' . basename($imagen["name"]), $id_categoria, $id_producto]);
                return true;
            } else {
                throw new Exception("Hubo un error al subir la imagen.");
            }
        } else {
            throw new Exception("El archivo no es una imagen.");
        }
    } else {
        // Actualizar producto en la base de datos sin la nueva imagen
        $stmt = $conn->prepare("UPDATE Productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, id_categoria = ? WHERE id_producto = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $id_producto]);
        return true;
    }
}
?>

