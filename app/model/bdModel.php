<?php
# <!-- Clase con los datos de acceso a la base de datos --><?php

class bd {
    protected $bbdd = "concierto";
    protected $username = "adminuser";
    protected $password = "1234";
    protected $conexion;

    public function __construct() {
        try {
            $this->conexion = new PDO('mysql:host=10.0.0.4;dbname=' . $this->bbdd, $this->username, $this->password);
            // Crear la base de datos si no existe
            $this->conexion->exec("CREATE DATABASE IF NOT EXISTS $this->bbdd");
            // Seleccionar la base de datos
            $this->conexion->exec("USE $this->bbdd");
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }
}
?>