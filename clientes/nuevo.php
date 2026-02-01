<?php

/**
 * Endpoint para crear un nuevo cliente.
 * 
 * Este archivo procesa las solicitudes POST para insertar un nuevo cliente
 * en la base de datos.
 * 
 * @package RestAPI\Endpoints
 */

// Headers necesarios.
// Permitimos que el archivo pueda ser leído por cualquier usuario.
header("Access-Control-Allow-Origin: *");
// Indicamos que el resultado se devolverá en un JSON y codificado en UTF-8
header("Content-Type: application/json; charset=UTF-8");
// Recibimos los datos por POST.
header("Access-Control-Allow-Methods: POST");
// Los datos se almacenarán en la cache como máximo 1 hora.
header("Access-Control-Max-Age: 3600");
// Cabeceras permitidas.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluimos la gestión de la tabla clientes.
include_once('../bd/clientes.php');
// JSON con el mensaje de respuesta.
$json = array();
// Si no nos envía los datos por POST.
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    // Código de respuesta - Method Not Alloweb
    http_response_code(405);
    $json['message'] = 'Sólo se permite el método POST.';
} else {
    // Verificamos que están los datos.
    if (isset($_POST['nombre']) && isset($_POST['email'])) {
        // Creamos el objeto para insertar el cliente.
        $clientes = new ClientesBD();
        // Le damos los valores a insertar.
        $clientes->nombre = $_POST['nombre'];
        $clientes->email = $_POST['email'];
        // Si no se ha podido insertar, error.
        if (($clientes->Insertar() == -1) || $clientes->Error()) {
            $json['message'] = 'Cliente no insertado.';
            // Código de respuesta - 501 Not implemented
            http_response_code(501);
        } else {
            $json['message'] = 'Cliente insertado.';
            // Código de respuesta - 201 Created
            http_response_code(201);
        }
    } else {
        // Código de respuesta - 400 Bad Resquest
        http_response_code(400);
        $json['message'] = 'Datos incompletos.';
    }
}
// Devolvemos el JSON
echo json_encode($json);
