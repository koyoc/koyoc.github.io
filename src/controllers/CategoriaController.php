<?php
// src/controllers/CategoriaController.php
include __DIR__ . '/../config/config.php';

function agregarCategoria($nombre_categoria) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Categorias (nombre) VALUES (?)");
    $stmt->execute([$nombre_categoria]);
    return $conn->lastInsertId();
}

?>
