<?php
/**
 * Clase Database
 * Maneja la conexión a SQL Server
 */
class Database {
    private $serverName = "bdagenda.c6z2gwa2ebvc.us-east-1.rds.amazonaws.com,1433";
    private $database = "BD_Agenda";
    private $uid = "admin";
    private $pwd = "v7c1Mj*X6";
    private $conn = null;

    /**
     * Obtiene la conexión a la base de datos
     * @return resource|false Conexión a SQL Server
     */
    public function getConnection() {
        if ($this->conn === null) {
            $connectionOptions = array(
                "Database" => $this->database,
                "Uid" => $this->uid,
                "PWD" => $this->pwd,
                "CharacterSet" => "UTF-8"
            );

            $this->conn = sqlsrv_connect($this->serverName, $connectionOptions);

            if ($this->conn === false) {
                die("Error de conexión: " . print_r(sqlsrv_errors(), true));
            }
        }

        return $this->conn;
    }

    /**
     * Cierra la conexión a la base de datos
     */
    public function closeConnection() {
        if ($this->conn !== null) {
            sqlsrv_close($this->conn);
            $this->conn = null;
        }
    }
}
?>
