<?php
/**
 * Clase Database
 * Maneja la conexión a SQL Server
 */
class Database {
    private $serverName = "PC_ValiSama";
    private $database = "BD_Agenda";
    private $uid = "";
    private $pwd = "";
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
