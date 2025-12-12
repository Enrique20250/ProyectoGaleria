<?php
class Imagen {
    private $id;
    private $titulo;
    private $descripcion;
    private $url;
    private $categoria_id;
    private $fecha_subida;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getCategoriaId() {
        return $this->categoria_id;
    }

    public function setCategoriaId($categoria_id) {
        $this->categoria_id = $categoria_id;
    }

    // Obtener todas las imágenes
    public function obtenerTodas() {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT i.*, c.nombre as categoria_nombre 
                    FROM imagenes i 
                    INNER JOIN categorias c ON i.categoria_id = c.id 
                    ORDER BY i.fecha_subida DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Buscar por categoría
    public function buscarPorCategoria($categoria_id) {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT i.*, c.nombre as categoria_nombre 
                    FROM imagenes i 
                    INNER JOIN categorias c ON i.categoria_id = c.id 
                    WHERE i.categoria_id = :categoria_id 
                    ORDER BY i.fecha_subida DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['categoria_id' => $categoria_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Buscar por texto en título o descripción
    public function buscarPorTexto($texto) {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT i.*, c.nombre as categoria_nombre 
                    FROM imagenes i 
                    INNER JOIN categorias c ON i.categoria_id = c.id 
                    WHERE i.titulo LIKE :texto OR i.descripcion LIKE :texto 
                    ORDER BY i.fecha_subida DESC";
            $stmt = $pdo->prepare($sql);
            $textoLike = "%{$texto}%";
            $stmt->execute(['texto' => $textoLike]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtener una imagen por ID
    public function obtenerPorId($id) {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT i.*, c.nombre as categoria_nombre 
                    FROM imagenes i 
                    INNER JOIN categorias c ON i.categoria_id = c.id 
                    WHERE i.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
