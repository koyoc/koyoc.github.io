<?php
// public/procesar_agregar_categoria.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/CategoriaController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_categoria = $_POST['nombre_categoria'];
    $id_categoria = agregarCategoria($nombre_categoria);
    
    echo json_encode([
        'id' => $id_categoria,
        'nombre' => htmlspecialchars($nombre_categoria)
    ]);
}

?>
