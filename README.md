# Prueba Tecnica Ilumno

* Se crea un modulo llamado example_module
* Se crea un formulario con los 4 campos solicitados.
* Se generan validaciones de obligatoriedad por cada campo utilizando el concepto de Ajax y el api de AjaxResponse
* Se crea una tabla llamada example_users con los campos del formulario, la cual se crea al momento de hacer la instalación del modulo, a traves del hook_scheme
* Se implementa regla de negocio para asignar valor al campo estado, dependiendo del cargo seleccionado
* Se almacena la información proveniente del formulario en la función submitForm
* Se valida obligatoriedad de campos al momento de hacer submit del formulario
* Para el campo de fecha de nacimiento se creo de tipo date, para que muestre un calendario como control
* El envio de los datos del formulario se hace a traves de Ajax
* Se muestra mensaje de resultado de insercción al usuario al final del proceso
* Se implemento un controlador para obtener el listado de los usuarios registrados


## Rutas
* example_module/form :  Carga el formulario de creación de usuario
* example_module/data :  Carga los datos del usuario en una tabla HTML


## Checklist Prueba

Las especificaciones del módulo se describen a continuación.
1. **(REALIZADO)** El módulo debe declarar dos rutas en el sistema /example-module/form y
/example-module/data
2. **(REALIZADO)** En la ruta /example-module/form se debe mostrar un formulario que tenga los
siguientes campos:
1. **(REALIZADO)** Nombre: Campo de texto. Este campo es requerido y solo acepta
caracteres alfanuméricos.
2. **(REALIZADO)** Identificación: Campo de texto. Este campo es requerido y solo acepta
números.
3. **(REALIZADO)** Fecha de nacimiento: Campo de fecha. Puede usar el formato que mejor
considere pero debe dar un texto de ayuda al usuario.
4. **(REALIZADO)** Cargo: Campo de selección. Las opciones son Administrador,
Webmaster, Desarrollador.
3. **(REALIZADO)** Al momento de la instalación el módulo debe crear una tabla que se llame “
example_users” y que contenga los campos que se mencionaron en el punto
anterior más un campo llamado “Estado”.
4. **(REALIZADO)** Validar obligatoriedad, formatos de los campos utilizando las funciones
apropiadas.
5. **(REALIZADO)** Al realizar el envío del formulario, llenar los datos en la tabla mencionada en el
paso anterior. Colocar el campo “Estado” en 1 si el cargo es “Administrador” o
0 para el resto de valores. Adicionalmente mostrar un mensaje en pantalla al
finalizar el envío del formulario.
6. **(REALIZADO)** Como punto adicional (No Obligatorio) se valora el desarrollo del formulario de
envió con Ajax (Usando el API de Ajax de Drupal)
