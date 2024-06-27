<?php
date_default_timezone_set('America/Santiago');
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

    function convertDateFormat($dateStr) {
        // Crear un objeto DateTime desde el formato original
         $dateTime =DateTime::createFromFormat('d/m/Y, H:i:s', $dateStr);
        
        if ($dateTime) {
            // Formatear el objeto DateTime al formato deseado
            $formattedDate = $dateTime->format('Y-m-d H:i:s');
            return $formattedDate;
        } else {
            // Manejar caso de fecha no válida
            echo 'Fecha no válida: ' . $dateStr;
            return null;
        }
    }
    //$dateTime=convertDateFormat($currentTime);

    $dateTime = date('Y-m-d H:i:s', time());
    $sql = "INSERT INTO registros (rut, patente, created_at, tipo, metodo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $rut, $patente, $dateTime, $tipo, $metodo);
    
    if ($stmt->execute()) {
        echo json_encode(["message" => $sql ]);
    } else {
        echo json_encode(["message" => "Error al guardar el registro"]);
    }

    $stmt->close();
}

$conn->close();
?>