<?php
class Favorito {
    private $idUsuario;
    private $idImagen;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getIdImagen() {
        return $this->idImagen;
    }

    public function setIdImagen($idImagen) {
        $this->idImagen = $idImagen;
    }

    public function marcar() {
        try {
            $pdo = $this->conexion->getPDO();
            
            $sqlCheck = "SELECT * FROM favoritos WHERE id_usuario = :id_usuario AND id_imagen = :id_imagen";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                'id_usuario' => $this->idUsuario,
                'id_imagen' => $this->idImagen
            ]);
            
            if ($stmtCheck->fetch()) {
                return false; 
            }
            
            $sql = "INSERT INTO favoritos (id_usuario, id_imagen) VALUES (:id_usuario, :id_imagen)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'id_usuario' => $this->idUsuario,
                'id_imagen' => $this->idImagen
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function desmarcar() {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "DELETE FROM favoritos WHERE id_usuario = :id_usuario AND id_imagen = :id_imagen";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'id_usuario' => $this->idUsuario,
                'id_imagen' => $this->idImagen
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerFavoritos() {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT i.*, c.nombre as categoria_nombre 
                    FROM favoritos f
                    INNER JOIN imagenes i ON f.id_imagen = i.id
                    INNER JOIN categorias c ON i.categoria_id = c.id
                    WHERE f.id_usuario = :id_usuario
                    ORDER BY f.fecha_agregado DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_usuario' => $this->idUsuario]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function esFavorito() {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT COUNT(*) as total FROM favoritos WHERE id_usuario = :id_usuario AND id_imagen = :id_imagen";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_usuario' => $this->idUsuario,
                'id_imagen' => $this->idImagen
            ]);
            $resultado = $stmt->fetch();
            return $resultado['total'] > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerIdsFavoritos() {
        try {
            $pdo = $this->conexion->getPDO();
            $sql = "SELECT id_imagen FROM favoritos WHERE id_usuario = :id_usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_usuario' => $this->idUsuario]);
            $favoritos = $stmt->fetchAll();
            
            $ids = [];
            foreach ($favoritos as $fav) {
                $ids[] = $fav['id_imagen'];
            }
            return $ids;
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
