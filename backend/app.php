<?php
header("Access-Control-Allow-Origin: *"); // Permitir cualquier origen
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Permitir JSON
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestionturnos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true) ?? [];
/*
if (!isset($data["tipo"])) {
    echo json_encode(["error" => "Tipo de turno no recibido"]);
    exit;
}*/

$tipo = $data["tipo"] ?? '';

if (!in_array($tipo, ['A', 'B', 'C'])) {
    echo json_encode(["error" => "Tipo de turno no válido"]);
    exit;
}

// Obtener el último número de turno para el tipo seleccionado
$sql = "SELECT numero FROM turnos WHERE tipo = '$tipo' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$numero = $row ? $row["numero"] + 1 : 1;

$turno = $tipo . str_pad($numero, 3, '0', STR_PAD_LEFT);

// Insertar en la base de datos
$sql = "INSERT INTO turnos (tipo, numero, turno) VALUES ('$tipo', $numero, '$turno')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["turno" => $turno]);
} else {
    echo json_encode(["error" => "Error al guardar el turno"]);
}

$conn->close();
?>
