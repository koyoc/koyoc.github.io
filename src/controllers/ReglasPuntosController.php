<?php
// src/controllers/ReglasPuntosController.php
include __DIR__ . '/../config/config.php';

function obtenerReglasPuntos() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM ReglasPuntos");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function actualizarReglaPuntos($id_regla, $monto, $puntos) {
    global $conn;
    $stmt = $conn->prepare("UPDATE ReglasPuntos SET monto = ?, puntos = ? WHERE id_regla = ?");
    return $stmt->execute([$monto, $puntos, $id_regla]);
}
?>
