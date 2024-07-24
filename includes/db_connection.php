<?php
class DBConnection {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "facultad";
    private $conn;

    public function __construct() {
        // Crear conexión
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Verificar la conexión
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}
?>