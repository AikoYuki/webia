<?php
header('Content-Type: application/json');

// Incluir configuración
include_once 'config.php';

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar datos básicos
if (empty($_POST['fecha']) || empty($_POST['nombre']) || !isset($_POST['comensales'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos']);
    exit;
}

// Sanitizar datos
$nombre = trim($_POST['nombre']);
$comensales = intval($_POST['comensales']);
$fecha = $_POST['fecha'];

// Validaciones
if (strlen($nombre) === 0 || strlen($nombre) > 100) {
    echo json_encode(['success' => false, 'message' => 'Nombre inválido']);
    exit;
}

if ($comensales < 0 || $comensales > 100) {
    echo json_encode(['success' => false, 'message' => 'Número de comensales inválido (0-100)']);
    exit;
}

if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
    echo json_encode(['success' => false, 'message' => 'Fecha inválida']);
    exit;
}

// Conectar a la base de datos
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si la fecha ya está ocupada
    $stmt = $pdo->prepare("SELECT id FROM reservas WHERE fecha = ?");
    $stmt->execute([$fecha]);
    $reservaExistente = $stmt->fetch();
    
    if ($reservaExistente) {
        echo json_encode(['success' => false, 'message' => 'Fecha para alquiler de salón ocupada, seleccione otra fecha.']);
        exit;
    }
    
    // Insertar nueva reserva
    $stmt = $pdo->prepare("INSERT INTO reservas (nombre, comensales, fecha) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $comensales, $fecha]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Reserva Exitosa']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar la reserva']);
    }
    
} catch (PDOException $e) {
    error_log("Error de base de datos: " . $e->getMessage());
    
    // Mensajes de error más específicos
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Fecha para alquiler de salón ocupada, seleccione otra fecha.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error del sistema. Por favor, intente más tarde.']);
    }
}
?>