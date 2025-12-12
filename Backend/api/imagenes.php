<?php
header('Content-Type: application/json');

try {
    require_once '../config/Conexion.php';
    require_once '../models/imagen.php';
    require_once '../models/categoria.php';

    $conexion = new Conexion();
    $imagenModel = new Imagen($conexion);
    $categoriaModel = new Categoria($conexion);

    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'todas';

    switch($accion) {
        case 'todas':
            $imagenes = $imagenModel->obtenerTodas();
            echo json_encode(['success' => true, 'data' => $imagenes]);
            break;
        
        case 'categoria':
            $categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : 0;
            $imagenes = $imagenModel->buscarPorCategoria($categoria_id);
            echo json_encode(['success' => true, 'data' => $imagenes]);
            break;
        
        case 'buscar':
            $texto = isset($_GET['texto']) ? $_GET['texto'] : '';
            $imagenes = $imagenModel->buscarPorTexto($texto);
            echo json_encode(['success' => true, 'data' => $imagenes]);
            break;
        
        case 'detalle':
            $id = isset($_GET['id']) ? $_GET['id'] : 0;
            $imagen = $imagenModel->obtenerPorId($id);
            echo json_encode(['success' => true, 'data' => $imagen]);
            break;
        
        case 'categorias':
            $categorias = $categoriaModel->obtenerTodas();
            echo json_encode(['success' => true, 'data' => $categorias]);
            break;
        
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }

    $conexion->terminar();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
