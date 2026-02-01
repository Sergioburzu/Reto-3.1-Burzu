<?php

/**
 * Endpoint para editar un cliente.
 * 
 * Este archivo procesa las solicitudes POST para actualizar los datos de un
 * cliente existente en la base de datos.
 * 
 * @package RestAPI\Endpoints
 */

// Headers necesarios.
// Permitimos que el archivo pueda ser leído por cualquier usuario.
header("Access-Control-Allow-Origin: *");
// Indicamos que el resultado se devolverá en un JSON y codificado en UTF-8
header("Content-Type: application/json; charset=UTF-8");
// Recibimos los datos por POST y GET.
header("Access-Control-Allow-Methods: POST, GET");
// Los datos se almacenarán en la cache como máximo 1 hora.
header("Access-Control-Max-Age: 3600");
// Cabeceras permitidas.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Incluimos la gestión de la tabla clientes.
include_once('../bd/clientes.php');
// Si no nos envían los datos por POST.
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    // Código de respuesta - Method Not Alloweb
    http_response_code(405);
    $json['message'] = 'Sólo se permite el método POST.';
} else {
    // JSON con el mensaje de respuesta.
    $json = array();
    // Verificamos que están los datos.
    if (
        isset($_POST['nombre']) &&
        isset($_POST['email']) &&
        isset($_GET['id'])
    ) {
        // Creamos el objeto para insertar el cliente.
        $clientes = new ClientesBD();
        // Le indicamos los datos
        $clientes
            ->id = $_GET['id'];
        $clientes
            ->nombre = $_POST['nombre'];
        $clientes
            ->email = $_POST['email'];
        // y los modificamos
        $filas = $clientes
            ->Modificar();
        // Si no se ha podido modificar.
        if (
            $filas ==
            -1 || $clientes
            ->Error()
        ) {
            $json['message'] = $clientes
                ->GetError();
            // Código de respuesta- 501 Not Implemented
            http_response_code(501);
        }
        // No se ha modificado nada.
        elseif ($filas == 0) {
            $json['message'] = 'Cliente no modificado.';
            // Código de respuesta- 400 Bad Request
            http_response_code(400);
        }
        // Todo correcto.
        else {
            $json['message'] = 'Cliente modificado.';
            // Código de respuesta- 200 OK
            http_response_code(200);
        }
    }
    // Si falta algún dato.
    else {
        $json['message'] = 'Datos incompletos.';
        // Código de respuesta - 400 Bad Resquest
        http_response_code(400);
    }
}
// Devolvemos el JSON
echo json_encode($json);
