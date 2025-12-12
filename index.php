<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'Backend/config/Conexion.php';
require_once 'Backend/models/Categoria.php';

$conexion = new Conexion();
$categoriaModel = new Categoria($conexion);
$categorias = $categoriaModel->obtenerTodas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Imágenes Interactiva</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Galería de Imágenes</h1>
            <p>Explora nuestra colección de fotografías</p>
            <div class="user-info">
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
            </div>
        </header>

        <div class="controls">
            <div class="search-box">
                <input type="text" id="busqueda" placeholder="Buscar imágenes..." />
                <button onclick="buscarImagenes()">Buscar</button>
      
            </div>

            <div class="filtros">
                <button class="btn-filtro active" onclick="filtrarPorCategoria('todas')">Todas</button>
                <?php foreach($categorias as $categoria): ?>
                    <button class="btn-filtro" onclick="filtrarPorCategoria(<?php echo $categoria['id']; ?>)">
                        <?php echo ucfirst($categoria['nombre']); ?>
                    </button>
                <?php endforeach; ?>
                <button class="btn-filtro btn-favoritos" onclick="verFavoritos()">⭐ Mis Favoritos</button>
            </div>
        </div>

        <div id="galeria" class="galeria">
            <p class="cargando">Cargando imágenes...</p>
        </div>
    </div>


    <div id="modal" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal()">&times;</span>
            <img id="modal-imagen" src="/placeholder.svg" alt="">
            <div class="modal-info">
                <button id="modal-favorito-btn" class="btn-favorito-modal" onclick="toggleFavoritoModal()">
                    <span id="modal-favorito-icono">☆</span> <span id="modal-favorito-texto">Agregar a favoritos</span>
                </button>
                <h2 id="modal-titulo"></h2>
                <p id="modal-descripcion"></p>
                <p id="modal-categoria"></p>
            </div>
        </div>
    </div>

    <script>
        const usuarioId = <?php echo $_SESSION['usuario_id']; ?>;
    </script>
    <script src="js/galeria.js"></script>
</body>
</html>
<?php
$conexion->terminar();
?>
