<?php
include('./conexion.php');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rut'])) {
    // Obtener el último registro del RUT
    $rut = $_GET['rut'];
    $sql = "SELECT tipo FROM registros WHERE rut = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rut);
    $stmt->execute();
    $result = $stmt->get_result();
    $lastRecord = $result->fetch_assoc();
    echo json_encode($lastRecord);
    $stmt->close();
}

$conn->close();
?>
