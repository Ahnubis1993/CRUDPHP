<?php
require_once 'db_connection.php';

class Teachers {
    private $conn;

    public function __construct() {
        $db = new DBConnection();
        $this->conn = $db->getConnection();
    }

    public function getAllProfessors() {
        $sql = "SELECT * FROM profesor";
        $result = $this->conn->query($sql);

        $professors = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $professors[] = $row;
            }
        }

        return $professors;
    }

    public function getColumnNames() {
        $sql = "SHOW COLUMNS FROM profesor";
        $result = $this->conn->query($sql);

        $columnNames = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $columnNames[] = $row['Field'];
            }
        }

        return $columnNames;
    }

    public function deleteProfessorById($idProfesor) {
        // Desactivar temporalmente las restricciones de clave externa
        $this->conn->query("SET FOREIGN_KEY_CHECKS=0");
    
        // Eliminar al profesor por su ID
        $sql = "DELETE FROM profesor WHERE idProfesor = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $idProfesor);
        $stmt->execute();
        $stmt->close();
    
        // Volver a activar las restricciones de clave externa
        $this->conn->query("SET FOREIGN_KEY_CHECKS=1");
    }

    public function updateProfessor($idProfesor, $nombre, $apellido1, $apellido2) {
        $this->conn->query("SET FOREIGN_KEY_CHECKS=0");

        // Consulta SQL para actualizar los datos del profesor
        $sql = "UPDATE profesor SET nombre = ?, apellido1 = ?, apellido2 = ? WHERE idProfesor = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);
    
        // Vincular parámetros
        $stmt->bind_param("ssss", $nombre, $apellido1, $apellido2, $idProfesor);
    
        // Ejecutar la consulta
        $stmt->execute();

         // Volver a activar las restricciones de clave externa
         $this->conn->query("SET FOREIGN_KEY_CHECKS=1");
    
        // Verificar si se realizó la actualización correctamente
        if ($stmt->affected_rows > 0) {
            // La actualización se realizó con éxito
            return true;
        } else {
            // La actualización falló
            return false;
        }
    
    }

    public function getProfessorById($idProfesor) {
        $sql = "SELECT * FROM profesor WHERE idProfesor = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProfesor);
        $stmt->execute();
        $result = $stmt->get_result();
        $professor = $result->fetch_assoc();
        $stmt->close();
        return $professor;
    }

        // Función para buscar profesores por un campo específico
        public function searchProfessorsByField($valor) {
            // Consulta SQL para buscar profesores por el campo especificado
            $sql = "SELECT * FROM profesor WHERE nombre LIKE ?";
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