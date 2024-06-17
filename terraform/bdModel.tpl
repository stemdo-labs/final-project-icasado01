<?php
# <!-- Clase con los datos de acceso a la base de datos --><?php

class bd {
    protected $bbdd = "concierto";
    protected $username = "admin";
    protected $password = "stemdo";
    protected $conexion;

    public function __construct() {
        try {
            $this->conexion = new PDO('mysql:host=${public_ip};dbname=' . $this->bbdd, $this->username, $this->password);
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