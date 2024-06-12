<?php
class EntradaModelo extends bd {

    private $datosEntrada;

    public function __construct(){
        // invoco al constructor de la clase padre que tiene la conexión
        parent::__construct();
    }

    public function listado(){
        $consulta = $this->conexion->prepare("SELECT * FROM entradas");
        $consulta->execute();
        $this->datosEntrada = $consulta->fetchAll();
        $consulta->closeCursor();
        $consulta = null;
        return $this->datosEntrada;
    } 

    public function add(){
        $dF = $this->validar();

        // si los datos son válidos, entramos
        if($dF['valido']==true){

            // genero la variable para la fecha actual
            $fecha = date('Y-m-d');

            $consulta = $this->conexion->prepare("INSERT INTO entradas (id, fecha, nombre, apellidos, telefono, tipo_entrada, cantidad, precio) VALUES (:i, '$fecha', :n, :a, :t, :ti, :c, :p)");
            
            // ASIGNO DATOS A LOS VALORES
            // Con el generador de UUID le doy un nuevo valor automáticamente
            $id = $this->generarUID();
            $consulta->bindParam(':i', $id);
            // con el método bindParam pasamos valores
            $consulta->bindParam(':n', $dF['valores']['nombre']);
            $consulta->bindParam(':a', $dF['valores']['apellidos']);
            $consulta->bindParam(':t', $dF['valores']['telefono']);
            $consulta->bindParam(':ti', $dF['valores']['tipo_entrada']);
            $consulta->bindParam(':c', $dF['valores']['cantidad']);

            // llamo al método de la misma clase para obtener el precio
            $precio = $this->getPrecio($dF);
            $consulta->bindParam(':p', $precio);
                        
            // EJECUTAMOS LA SENTENCIA
            $consulta->execute();
            
            // llegados hasta aquí, todo ha salido bien, guardamos OK en la variable datosEntrada
            $this->datosEntrada = 'OK';
            $consulta->closeCursor();
            $consulta = null;

        }
        // si no son válidos, la variable datosEntrada no será OK
        else{
            $this->datosEntrada = $dF;
        } 
        return $this->datosEntrada;
    }

    private function getPrecio($dF){
        $modeloTipoEntrada = new TipoEntradaModelo();
        $datosTipoEntrada = $modeloTipoEntrada->listado();

        if(!empty($datosTipoEntrada)){
            foreach ($datosTipoEntrada as $dT):
                // si el id de la tabla tipo_entradas es igual al que hemos guardado en el array $df, calculamos el precio
                if ($dT['id']==$dF['valores']['tipo_entrada']) {
                    $precio = $dT['precio'] * $dF['valores']['cantidad'];
                }
            endforeach;
        }
        return $precio;
    }

    private function getTipoEntrada($dF){
        $modeloTipoEntrada = new TipoEntradaModelo();
        $datosTipoEntrada = $modeloTipoEntrada->listado();

        if(!empty($datosTipoEntrada)){
            foreach ($datosTipoEntrada as $dT):
                // si el id de la tabla tipo_entradas es igual al que hemos guardado en el array $df, calculamos el precio
                if ($dT['id']==$dF['valores']['tipo_entrada']) {
                    $tipoEntrada = $dT['tipo'];
                }
            endforeach;
        }
        return $tipoEntrada;
    }

    private function validar(){
        // Creamos arrays para las comprobaciones
        $dataF=array();
        $dataF['valido'] = true;
        $dataF['errores'] = array();
    
        $dataF['valores'] = array();
    
        $dataF['valores']['nombre'] = '';
        $dataF['valores']['apellidos']= '';
        $dataF['valores']['telefono']= '';
        $dataF['valores']['tipo_entrada']= '';
        $dataF['valores']['cantidad']= '';
    
    
        // Validamos los datos
        if(isset($_POST) && !empty($_POST)){
            /////////////////////////////////
            if(isset($_POST['nombre'])){
                $dataF['valores']['nombre']=$_POST['nombre'];
            }
            if(isset($_POST['apellidos'])){
                $dataF['valores']['apellidos']=$_POST['apellidos'];
            }
            if(isset($_POST['telefono'])){
                $dataF['valores']['telefono']=$_POST['telefono'];
            }
            if(isset($_POST['tipo_entrada'])){
                $dataF['valores']['tipo_entrada']=$_POST['tipo_entrada'];
            }
            if(isset($_POST['cantidad'])){
                $dataF['valores']['cantidad']=$_POST['cantidad'];
            }
            /////////////////////////////////
            if(isset($_POST['nombre']) && empty($_POST['nombre'])){
                $dataF['valido'] = false;
                $dataF['errores']['nombre']='El campo no puede estar vacío.';
            }
            if(isset($_POST['apellidos']) && empty($_POST['apellidos'])){
                $dataF['valido'] = false;
                $dataF['errores']['apellidos']='El campo no puede estar vacío.';
            }
            if(isset($_POST['telefono']) && empty($_POST['telefono'])){
                $dataF['valido'] = false;
                $dataF['errores']['telefono']='El campo no puede estar vacío.';
            }
            if(isset($_POST['tipo_entrada']) && empty($_POST['tipo_entrada'])){
                $dataF['valido'] = false;
                $dataF['errores']['tipo_entrada']='Debe seleccionar una opción.';
            }
            if(isset($_POST['cantidad']) && empty($_POST['cantidad'])){
                $dataF['valido'] = false;
                $dataF['errores']['cantidad']='El campo no puede estar vacío.';
            }
    
            // Comprobaciones específicas para teléfono y cantidad
            if(isset($_POST['telefono']) && !empty($_POST['telefono']) && preg_match("/^[0-9]{9}$/", $_POST['telefono'])==0){
                $dataF['valido'] = false;
                $dataF['errores']['telefono']='Debe tener una longitud de 9 dígitos.';
            }
            if(isset($_POST['telefono']) && !empty($_POST['telefono']) && !is_numeric($_POST['telefono']) ){
                $dataF['valido']=false;
                $dataF['errores']['telefono']='El campo debe ser un número.';
            }
            if(isset($_POST['cantidad']) && !empty($_POST['cantidad']) && !is_numeric($_POST['cantidad']) ){
                $dataF['valido']=false;
                $dataF['errores']['cantidad']='El campo debe ser un número.';
            }
            if(isset($_POST['cantidad']) && !empty($_POST['cantidad']) && is_numeric($_POST['cantidad'])
                && ($_POST['cantidad']<=0 || $_POST['cantidad']>10)){
                $dataF['valido']=false;
                $dataF['errores']['cantidad']='Debe ser un número positivo entre 1 y 10.';
            }
        } else{
            // La primera vez que entro
            $dataF['valido'] = false;
        }
        return $dataF;
    }

    // Método para generar el UID automáticamente
    private function generarUID(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
    }

    // Método que muestro el mensaje de confirmación de los datos
    public function getMensaje() {
        $dF = $this->validar();

        // variables generadas para el informe de que todo está correcto
            $nombre = $dF['valores']['nombre'].' '.$dF['valores']['apellidos'];
            $telefono = $dF['valores']['telefono'];
            $cantidad = $dF['valores']['cantidad'];
            $fecha = date('Y-m-d');
            // llamo al método de la misma clase para obtener el precio
            $precio = $this->getPrecio($dF);
            $tipo = $this->getTipoEntrada($dF);
        
            echo  "<script>
                    alert('Su compra se ha realizado correctamente.\\n\\n'+
                ' -Fecha: $fecha\\n'+
                ' -Nombre: $nombre\\n'+
                ' -Teléfono: $telefono\\n'+
                ' -Tipo de entrada: $tipo\\n' +
                ' -Cantidad: $cantidad\\n'+
                ' -Precio total: $precio €');
                window.location.href='index.php?controlador=entradas&action=listado';
                </script>" ;
    }
}