<!DOCTYPE html>
<html>
    <head>
        <title>Compra entradas</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="vista/css/estilos.css">
    </head>
    <body>
            <!-- CREACIÓN DE FORMULARIO -->
            <form action="index.php?controlador=entradas&action=add" method=post>
                <fieldset>
                    <legend><h2>COMPRA DE ENTRADAS</h2></legend>
                    <label>Nombre:</label><br/>
                    <!-- En el value indico con php que me inserte el valor en el input en caso de que hubiese -->
                    <input type="text" name="nombre" value="<?php echo $dF['valores']['nombre'];?>" /><br />
                    <!-- Si la variable de error está definida y no vacía se le mostrará el mensaje de error en rojo -->
                    <?php
                    if(isset($dF['errores']['nombre']) && !empty($dF['errores']['nombre'])){
                        echo '<span class="rojo">'.$dF['errores']['nombre'].'</span><br/>';
                    }
                    ?> <br/>
                    <label>Apellidos:</label><br/>
                    <input type="text" name="apellidos" value="<?php echo $dF['valores']['apellidos'];?>"/><br />
                    <?php
                    if(isset($dF['errores']['apellidos']) && !empty($dF['errores']['apellidos'])){
                        echo '<span class="rojo">'.$dF['errores']['apellidos'].'</span><br/>';
                    }
                    ?><br/>
                    <label>Teléfono:</label><br/>
                    <input type="text" name="telefono" value="<?php echo $dF['valores']['telefono'];?>"/><br />
                    <?php
                    if(isset($dF['errores']['telefono']) && !empty($dF['errores']['telefono'])){
                        echo '<span class="rojo">'.$dF['errores']['telefono'].'</span><br/>';
                    }
                    ?><br/>
                    <label>Tipo de entrada:</label><br/>
                    <select name="tipo_entrada" >
                        <option value="" disabled>Seleccione</option>
                        <!-- Sacamos las opciones de tipo de entrada de la BD
                        esta variable ($datosTipoEntrada) nos viene dada ya que en el método estático add() del controlador, 
                        creo un objeto de la clase TipoEntradaModelo, de la cuál uso el método listado y vuelco los datos de la bbdd en esa variable -->
                        <?php
                            if(!empty($datosTipoEntrada)){
                                foreach ($datosTipoEntrada as $dT): // los mostramos recorriendo el array con los datos
                                    if($dT['tipo']==$dF['valores']['tipo_entrada']){
                                    echo '<option value="'.$dT['id'].'" selected>'.$dT['tipo'].'</option>';
                                }else{
                                    echo '<option value="'.$dT['id'].'">'.$dT['tipo'].'</option>';
                                }
                            endforeach;
                            }
                        ?>
                    </select><br/>
                    <?php
                    if(isset($dF['errores']['tipo_entrada']) && !empty($dF['errores']['tipo_entrada']))
                    {
                        echo '<span class="rojo">'.$dF['errores']['tipo_entrada'].'</span><br/>';
                    }
                    ?>
                    <br/>
                    <label>Cantidad:</label><br/>
                    <input type="number" name="cantidad" value="<?php echo $dF['valores']['cantidad'];?>"/><br />
                    <?php
                    if(isset($dF['errores']['cantidad']) && !empty($dF['errores']['cantidad'])){
                        echo '<span class="rojo">'.$dF['errores']['cantidad'].'</span><br/>';
                    }
                    ?><br/>
                    <button type="submit" class="botonAceptar">Comprar</button>
                </fieldset>

                <br><a href="index.php?controlador=entradas&action=listado"> VOLVER AL LISTADO</a>  

            </form>
        </div>
    </body>
</html>