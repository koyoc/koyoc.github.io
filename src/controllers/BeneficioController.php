<?php
// src/controllers/BeneficioController.php
include __DIR__ . '/../config/config.php';

function getBeneficios() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Beneficios WHERE activo = TRUE");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchBeneficios($search) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Beneficios WHERE nombre_empresa LIKE ? AND activo = TRUE");
    $search = "%$search%";
    $stmt->execute([$search]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarBeneficio($nombre_empresa, $descripcion, $imagen) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Beneficios (nombre_empresa, descripcion, imagen, activo) VALUES (?, ?, ?, TRUE)");
    $stmt->execute([$nombre_empresa, $descripcion, $imagen]);
}

function eliminarBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Beneficios SET activo = FALSE WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
}

function actualizarBeneficio($id_beneficio, $nombre_empresa, $descripcion, $imagen) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Beneficios SET nombre_empresa = ?, descripcion = ?, imagen = ? WHERE id_beneficio = ?");
    $stmt->execute([$nombre_empresa, $descripcion, $imagen, $id_beneficio]);
}

function obtenerBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Beneficios WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function generateVerificationCode($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generarCupon($id_beneficio) {
    global $conn;
    $codigo_verificacion = generateVerificationCode();
    $stmt = $conn->prepare("INSERT INTO Cupones (id_beneficio, codigo_verificacion) VALUES (?, ?)");
    $stmt->execute([$id_beneficio, $codigo_verificacion]);

    return $codigo_verificacion;
}

function obtenerCupon($codigo_verificacion) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Cupones WHERE codigo_verificacion = ?");
    $stmt->execute([$codigo_verificacion]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function usarCupon($codigo_verificacion) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Cupones SET usado = TRUE WHERE codigo_verificacion = ?");
    $stmt->execute([$codigo_verificacion]);
}

function verificarCupon($codigo_verificacion) {
    global $conn;
    $cupon = obtenerCupon($codigo_verificacion);
    if ($cupon && !$cupon['usado']) {
        return true;
    }
    return false;
}

function getCupones() {
    global $conn;
    $stmt = $conn->query("SELECT Cupones.codigo_verificacion, Cupones.fecha_generado, Cupones.usado, Beneficios.nombre_empresa 
                        FROM Cupones 
                        JOIN Beneficios ON Cupones.id_beneficio = Beneficios.id_beneficio");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function eliminarCupon($codigo_verificacion) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Cupones WHERE codigo_verificacion = ?");
    $stmt->execute([$codigo_verificacion]);
}
?>
