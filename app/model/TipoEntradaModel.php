<?php
class TipoEntradaModelo extends bd {

    private $datosTipoEntrada;

    public function __construct(){
        // invoco al constructor de la clase padre que tiene la conexión
        parent::__construct();
    }

    public function listado(){
        $consulta = $this->conexion->prepare("SELECT * FROM tipo_entradas");
        $consulta->execute();
        $this->datosTipoEntrada = $consulta->fetchAll();
        $consulta->closeCursor();
        $consulta = null;
        return $this->datosTipoEntrada;
    }

}