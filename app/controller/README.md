Desarrollo Web  Entorno Servidor Aplicación “Venta de entradas para conciertos”
¿Qué te pedimos que hagas?
Transforma la aplicación de "Venta de entradas para conciertos" de la Unidad 2 e implementa todas sus funcionalidades utilizando un patrón MVC (Modelo-Vista-Controlador) donde deberás tener la siguiente estructura mínima de archivos y carpetas:
•	Models (dir)
o	EntradasModel.php
o	TipoEntradasModel.php
•	Controllers (dir)
o	EntradasController.php
•	Views (dir)
o	ComprarEntradasView.php
o	ListadoEntradasView.php
IMPORTANTE: Es obligatorio utilizar Programación Orientada a Objetos para realizar la tarea.
DESCRIPCIÓN DE LA TAREA 2:
Apartado 1.- Crea en PhpMyadmin una base de datos "concierto" con las siguientes tablas y campos:
entradas:
•	"id": campo de texto para almacenar UUID
•	"fecha": de tipo fecha (date).
•	"nombre": campo de texto de 15 caracteres.
•	"apellidos": campo de texto de 30 caracteres
•	"telefono": campo de texto de 9 caracteres.
•	"tipo_entrada": UUID
•	"cantidad": campo numérico de tipo entero.
•	"precio": campo numérico de tipo real.
•	tipo_entradas:
o	"id": campo de texto para almacenar UUID.
o	"tipo": campo de texto de 30 caracteres.
o	"precio": campo numérico de tipo real.
Elabora un pequeña memoria explicativa "Creación_BBDD.pdf" donde describas el proceso que has seguido para crear la BBDD. Adjunta suficientes capturas de pantalla.
NOTA. La tabla "tipo_entradas" se rellenará manualmente a través de PhpMyadmin y tendrá 3 registros:
o	tipo: "Menor", precio: "10";
o	tipo: "Adulto", precio: "5";
o	tipo: "Jubilado", precio "7";
Apartado 2.- Crear un script PHP "comprarentradas.php" para gestionar la compra de entradas mediante un formulario. 
Este script tendrá las siguientes funcionalidades:
•	El formulario tendrá un título, un conjunto de campos con su correspondiente "Label" y un botón "Comprar".
•	El formulario implementará los siguientes campos información que tendrán que rellenar los usuarios:
o	Nombre.
o	Apellidos.
o	Teléfono.
o	Tipo de entrada.
o	Cantidad.
•	Todos los campos serán obligatorios y no podrán estar vacíos. Además se tendrán que cumplir las siguientes condiciones específicas:
o	El campo "Teléfono" deberá ser de tipo numérico y con una longitud de 9 dígitos.
o	El campo "Cantidad" deberá ser de tipo numérico, y será un entero mayor que 0 y
o	menor o igual que 10.
o	El campo "tipo de entrada" deberá ser un desplegable que obtenga sus posibles opciones de la tabla "tipo_entradas".
•	Se implementarán todos los mensajes de error necesarios para avisar al usuario en caso de que no se cumplan algunas de las condiciones descritas anteriormente.
•	Si todos los datos son correctos, al enviar el formulario se llevarán a cabo las siguientes acciones en el servidor:
o	Se generará la ID del nuevo registro mediante UUID.
o	Se obtendrá la fecha del sistema de manera automática.
o	Se calculará el campo "precio" de manera automática mediante la cantidad y el precio unitario en función del tipo de entrada.
o	Se almacenará el registro en la BBDD.
•	Se mostrará un mensaje indicando que la venta se ha registrado correctamente, junto con un informe que indique la siguiente información:
o	Fecha de venta.
o	Nombre completo.
o	Teléfono de contacto.
o	Tipo de entrada comprada.
o	Cantidad de entradas compradas.
o	Precio total de la compra.
Apartado 3.- Crea un script PHP "listadoentradas.php" para mostrar las entradas que se han comprado a través del sistema. Este script tendrá las siguientes funcionalidades:
•	Se extraerán los registros almacenados en la tabla "entradas".
•	Se mostrará el listado con las entradas vendidas (en forma de tabla), ordenadas por fecha y con la siguiente información:
o	Fecha (en formato DD/MM/YYYY).
o	Nombre completo.
o	Teléfono.
o	Tipo de entrada (información extraída de la tabla "tipo_entrada").
o	Número de entradas.
o	Precio total en euros (información calculada mediante el número de entradas y el precio unitario del tipo de entrada).
IMPORTANTE:
DEBERÁS COMENTAR EL CÓDIGO DETALLADAMENTE.
•	La conexión a la BBDD se realizará mediante PDO.
•	Se realizará un fichero config.php con los datos de acceso a la BBDD
•	La conexión y desconexión a la BBDD también se realizará mediante los ficheros:
o	conexionbd.php y desconectabd.php
•	Los ficheros anteriormente descritos se incluirán mediante "include" en los scripts de PHP de los apartados 2 y 3.
