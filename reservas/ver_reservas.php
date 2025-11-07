<?php
include_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT * FROM reservas ORDER BY fecha DESC");
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Lista de Reservas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Comensales</th>
                    <th>Fecha</th>
                    <th>Fecha Creación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reserva['id']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['comensales']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_creacion']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.html" class="btn btn-primary">Nueva Reserva</a>
    </div>
</body>
</html>