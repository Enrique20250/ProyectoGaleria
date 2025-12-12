<?php
class Conexion {
    private $host = 'localhost';
    private $dbname = 'galeria';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        $this->iniciar();
    }

    public function iniciar() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8", 
                $this->username, 
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function terminar() {
        $this->pdo = null;
    }
}
?>
