<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

require_once '../config/Conexion.php';
require_once '../models/favorito.php';

$conexion = new Conexion();
$favoritoModel = new Favorito($conexion);

$idUsuario = $_SESSION['usuario_id'];

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch($accion) {
    case 'marcar':
        $idImagen = isset($_GET['id_imagen']) ? intval($_GET['id_imagen']) : 0;
        if ($idImagen > 0) {
            $favoritoModel->setIdUsuario($idUsuario);
            $favoritoModel->setIdImagen($idImagen);
            $resultado = $favoritoModel->marcar();
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Imagen agregada a favoritos']);
            } else {
                echo json_encode(['success' => false, 'message' => 'La imagen ya está en favoritos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de imagen inválido']);
        }
        break;
    
    case 'desmarcar':
        $idImagen = isset($_GET['id_imagen']) ? intval($_GET['id_imagen']) : 0;
        if ($idImagen > 0) {
            $favoritoModel->setIdUsuario($idUsuario);
            $favoritoModel->setIdImagen($idImagen);
            $resultado = $favoritoModel->desmarcar();
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Imagen eliminada de favoritos']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar favorito']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de imagen inválido']);
        }
        break;
    
    case 'obtener':
        $favoritoModel->setIdUsuario($idUsuario);
        $favoritos = $favoritoModel->obtenerFavoritos();
        echo json_encode(['success' => true, 'data' => $favoritos]);
        break;
    
    case 'verificar':
        $idImagen = isset($_GET['id_imagen']) ? intval($_GET['id_imagen']) : 0;
        if ($idImagen > 0) {
            $favoritoModel->setIdUsuario($idUsuario);
            $favoritoModel->setIdImagen($idImagen);
            $esFavorito = $favoritoModel->esFavorito();
            echo json_encode(['success' => true, 'esFavorito' => $esFavorito]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de imagen inválido']);
        }
        break;
    
    case 'ids':
        $favoritoModel->setIdUsuario($idUsuario);
        $ids = $favoritoModel->obtenerIdsFavoritos();
        echo json_encode(['success' => true, 'data' => $ids]);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

$conexion->terminar();
?>
