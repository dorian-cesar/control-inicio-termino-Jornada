<?php
include './conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $rut = $data['rut'];
    $patente = $data['patente'];
    $currentTime = $data['currentTime'];
    $tipo = $data['tipo']; 
    $metodo = $data['metodo']; // Siempre se envía el método
    

    $sql = "INSERT INTO registros (rut, patente, timestamp, tipo, metodo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $rut, $patente, $currentTime, $tipo, $metodo);
    
    if ($stmt->execute()) {
        echo json_encode(["message" => "Registro guardado correctamente"]);
    } else {
        echo json_encode(["message" => "Error al guardar el registro"]);
    }

    $stmt->close();
}

$conn->close();
?>