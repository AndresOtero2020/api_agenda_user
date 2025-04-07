<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'agenda_db';
    private $username = 'root'; // Cambia a tu usuario de MySQL
    private $password = ''; // Cambia a tu contraseña de MySQL
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Conexión fallida: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
