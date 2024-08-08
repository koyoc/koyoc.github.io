<?php
// public/eliminar_producto.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ProductoController.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        eliminarProducto($id);
        header("Location: productos.php?mensaje=Producto eliminado exitosamente");
    } catch (Exception $e) {
        header("Location: productos.php?error=" . urlencode($e->getMessage()));
    }
}

function eliminarProducto($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Productos WHERE id = ?");
    $stmt->execute([$id]);
}
?>
