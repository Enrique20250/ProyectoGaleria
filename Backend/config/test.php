<?php
require_once(__DIR__ . '/Conexion.php');

try {

    $conexion = new Conexion();
    $pdo = $conexion->getPDO();

    echo "=== PRUEBA DE CONEXIÓN A BASE DE DATOS ===\n\n";
    echo "✓ Conexión exitosa a la base de datos\n\n";
    echo "-------------------------------------------\n\n";

    echo "TABLA: categorias\n";
    echo "-------------------------------------------\n";
    $stmt = $pdo->query("SELECT * FROM categorias");
    $categorias = $stmt->fetchAll();

    if (count($categorias) > 0) {
        foreach ($categorias as $cat) {
            echo "ID: " . $cat['id'] . " | ";
            echo "Nombre: " . $cat['nombre'] . " | ";
            echo "Descripción: " . $cat['descripcion'] . "\n";
        }
        echo "\nTotal de categorías: " . count($categorias) . "\n\n";
    } else {
        echo "No hay categorías en la base de datos.\n\n";
    }

    echo "-------------------------------------------\n\n";
    echo "TABLA: imagenes\n";
    echo "-------------------------------------------\n";
    $stmt = $pdo->query("SELECT i.*, c.nombre as categoria_nombre 
                         FROM imagenes i 
                         LEFT JOIN categorias c ON i.categoria_id = c.id 
                         LIMIT 10");
    $imagenes = $stmt->fetchAll();

    if (count($imagenes) > 0) {
        foreach ($imagenes as $img) {
            echo "ID: " . $img['id'] . " | ";
            echo "Título: " . $img['titulo'] . " | ";
            echo "Categoría: " . $img['categoria_nombre'] . "\n";
        }

        $stmt = $pdo->query("SELECT COUNT(*) as total FROM imagenes");
        $total = $stmt->fetch();
        echo "\nTotal de imágenes: " . $total['total'] . "\n\n";
    } else {
        echo "No hay imágenes en la base de datos.\n\n";
    }

    echo "-------------------------------------------\n";
    echo "✓ Todas las pruebas completadas exitosamente\n";

    $conexion->terminar();

} catch (PDOException $e) {
    echo "✗ Error de conexión\n";
    echo "Error: " . $e->getMessage() . "\n";
}
