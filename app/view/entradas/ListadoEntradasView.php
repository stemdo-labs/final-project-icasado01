<!DOCTYPE html>
<html>
    <head>
        <title>Listado de entradas</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="vista/css/estilos.css">
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">LISTADO DE ENTRADAS - cambio</h1>
    <?php
            // comprobamos que las dos variables con las consultas no estén vacías
            if(!empty($datosEntrada) && !empty($datosTipoEntrada)){
                echo '<table>
                        <tr>
                            <th> Fecha </th>
                            <th> Nombre </th>
                            <th> Teléfono </th>
                            <th> Tipo </th>
                            <th> Cantidad </th>
                            <th> Precio </th>
                        </tr>';
                // recorremos el array con los datos de la tabla entradas
                foreach($datosEntrada as $dE){
                    echo '<tr>
                            <td>';
                        // Creo un nuevo objeto de tipo DateTime para poder formatearlo
                        $fecha = new DateTime($dE['fecha']);
                        echo date_format($fecha, 'd/m/Y');
                        echo '</td>';
                        echo '<td>';
                        echo $dE['nombre'].' '.$dE['apellidos'];
                        echo '</td>';
                        echo '<td>';
                        echo $dE['telefono'];
                        echo '</td>';
                        echo '<td>';

                    foreach ($datosTipoEntrada as $dT): // los mostramos recorriendo el array con los datos de la tabla tipo_entradas
                    // Comparo ids
                    // Si el id de la tabla tipo_entradas (variable $dT['id']) es igual 
                    // al tipo_entrada de la tabla entradas ($dE['tipo_entrada']), muestro el tipo de la tabla tipo_entradas
                        if($dT['id']==$dE['tipo_entrada']){
                                echo $dT['tipo'];
                        }
                    endforeach;
                        echo '</td>';
                        echo '<td>';
                        echo $dE['cantidad'];
                        echo '</td>';
                        echo '<td>';
                    // EXTRAEMOS EL PRECIO DE LA TABLA TIPO_ENTRADAS
                    foreach ($datosTipoEntrada as $dT): 
                        if($dT['id']==$dE['tipo_entrada']){
                            // con la variable $r obtenemos los datos de la tabla entradas
                            echo $dT['precio'] * $dE['cantidad'];
                        }
                        endforeach;
                        // echo $r['precio'];
                        echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            // Si no hay un registro mostrará lo siguiente
            else{
                echo '<h3>No existe ningún registro en la base de datos</h3>';
            }
            ?>
            
            <a href="index.php?controlador=entradas&action=add"><button class="botonAceptar">Comprar entradas</button></a> 
        </div>  
    </body>
</html>  
