<?php
include_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión exitosa a la base de datos!<br>";
    
    // Probar consulta
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservas");
    $result = $stmt->fetch();
    echo "Total de reservas: " . $result['total'];
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>