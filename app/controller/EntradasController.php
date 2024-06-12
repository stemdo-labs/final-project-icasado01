<?php
require_once("model/EntradaModel.php");
require_once("model/TipoEntradaModel.php");

class EntradasControlador{
    
    public function __construct(){

    }

    static function listado(){
        // debemos instanciar las clases modelo para ejecutar sus métodos
        $modeloEntrada = new EntradaModelo();
        $modeloTipoEntrada = new TipoEntradaModelo();

        // obtenemos los datos de las consultas en su modelo correspondiente y su método
        // CADA TABLA TIENE SU MODELO
        $datosEntrada = $modeloEntrada->listado();
        $datosTipoEntrada = $modeloTipoEntrada->listado();

        // las variable con los datos se pasan al archivo incluido en este método estático
        require_once("view/entradas/ListadoEntradasView.php");
    }

    static function add(){
        $modeloEntrada = new EntradaModelo();
        $modeloTipoEntrada = new TipoEntradaModelo();

        $datosEntrada = $modeloEntrada->listado();
        $datosTipoEntrada = $modeloTipoEntrada->listado();

        $dF = $modeloEntrada->add();

        // comprobamos si el método add nos ha devuelto el OK, para confirmar que se añadió correctamente y así ir directamente a mostrar el listado
        if($dF == 'OK'){

            // mostramos el mensaje de confirmación llamando al método adecuado
            $modeloEntrada->getMensaje();

            exit();
        // si no es OK, nos devolverá de nuevo a la compra
        }else{
            require_once("view/entradas/ComprarEntradasView.php");
        }
    }
}