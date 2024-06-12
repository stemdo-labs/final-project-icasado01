<?php
// require_once, para que el archivo se incluya sólo una vez
require_once("model/bdModel.php");

// compruebo de primeras si la variable GET contiene información
if(isset($_GET['controlador']) && !empty($_GET['controlador']) && isset($_GET['action']) && !empty($_GET['action'])){
    // en el caso de que la variable GET controlador contenga algo, comprobamos qué es
    switch($_GET['controlador'])
    {
        case 'entradas':
            require_once("controller/EntradasController.php");
            // compruebo si existe el método que me llega por GET[action], en la clase EntradasControlador
            // si existe, con :: lo ejecuto, ya que es un método estático, si no, muestro el listado
            if(method_exists("EntradasControlador", $_GET['action'])){
                EntradasControlador::{$_GET['action']}();
            }
            else{
                EntradasControlador::listado();
            }            
        break;
        default:
            header("Status: 301 Moved Permanently");
            header("Location: index.php?controlador=entradas&action=listado");
            exit();
        break;
    }
}   
else{
    // de primeras se va por aquí, y creamos manualmente las variables GET, por defecto listamos
    header("Status: 301 Moved Permanently");
    header("Location: index.php?controlador=entradas&action=listado");
    exit();
}

