<?php
require 'db_connection.php';

echo "<h1>Prueba de Base de Datos</h1>";

if ($pdo) {
    echo "<p style='color: green; font-weight: bold;'>¡CONEXIÓN EXITOSA!</p>";
    
    // Probamos trayendo datos
    $stmt = $pdo->query("SELECT * FROM categorias");
    $categorias = $stmt->fetchAll();

    echo "<h3>Categorías encontradas:</h3><ul>";
    foreach ($categorias as $cat) {
        echo "<li>" . $cat['nombre'] . "</li>";
    }
    echo "</ul>";
}
?>
