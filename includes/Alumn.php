<?php
require_once 'db_connection.php';

class Alumn {
    private $conn;

    public function __construct() {
        $db = new DBConnection();
        $this->conn = $db->getConnection();
    }

    public function getAllAlumns() {
        $sql = "SELECT * FROM alumno";
        $result = $this->conn->query($sql);

        $alumnos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alumnos[] = $row;
            }
        }

        return $alumnos;
    }

    public function getColumnNames() {
        $sql = "SHOW COLUMNS FROM alumno";
        $result = $this->conn->query($sql);

        $columnNames = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $columnNames[] = $row['Field'];
            }
        }

        return $columnNames;
    }

    public function updateAlumn($idAlumno, $nombre, $apellido) {
        $sql = "UPDATE alumno SET nombre = ?, apellido1 = ? WHERE idAlumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $apellido, $idAlumno); 
        $stmt->execute();
        $stmt->close();
    }

    public function deleteAlumnById($idAlumno) {
        $this->conn->query("SET FOREIGN_KEY_CHECKS=0");

        $sql = "DELETE FROM alumno WHERE idAlumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $idAlumno);
        $stmt->execute();
        $stmt->close();

        $this->conn->query("SET FOREIGN_KEY_CHECKS=1");

    }

    public function searchAlumnsByField($valor) {
        // Consulta SQL para buscar profesores por el campo especificado
        $sql = "SELECT * FROM alumno WHERE nombre LIKE ?";
        $stmt = $this->conn->prepare($sql);
        
        // Preparar el valor para la consulta (agregando comodines para la búsqueda)
        $stmt->bind_param("s", $valor);
        
        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        // Almacenar los resultados en un array
        $professors = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $professors[] = $row;
            }
        }

        // Cerrar la consulta
        $stmt->close();

        // Devolver los resultados de la búsqueda
        return $professors;
    }

}
?>