<?php
class Categoria {
    private $id;
    private $nombre;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Obtener todas las categorías
    public function obtenerTodas() {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT * FROM categorias ORDER BY nombre";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
