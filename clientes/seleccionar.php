<?php

/**
 * Endpoint para seleccionar clientes.
 * 
 * Este archivo procesa las solicitudes GET para obtener uno o todos los
 * clientes de la base de datos.
 * 
 * @package RestAPI\Endpoints
 */

// Headers necesarios.
// Permitimos que el archivo pueda ser leído por cualquier usuario.
header("Access-Control-Allow-Origin: *");
// Indicamos que el resultado se devolverá en un JSON y codificado en UTF-8
header("Content-Type: application/json; charset=UTF-8");
// Recibimos los datos por GET.
header("Access-Control-Allow-Methods: GET");
include_once('../bd/clientes.php');
// JSON que devolveremos con un mensaje, el número y un vector con los clientes.
$json = array(
    'message' => '',
    'recordscount' => 0,
    'records' => array()
);
// Si no nos envían los datos por GET.
if ($_SERVER['REQUEST_METHOD'] != "GET") {
    // Código de respuesta - Method Not Alloweb
    http_response_code(405);
    $json['message'] = 'Sólo se permite el método GET.';
} else {
    // Creamos el objeto para obtener los clientes.
    $clientes = new ClientesBD();
    // Si me han pasado un id, se lo asignamos al objeto clientes.
    if (isset($_GET['id']))
        $clientes->id = $_GET['id'];
    // Obtenemos los clientes.
    if ($clientes->Seleccionar()) {
        // Número de clientes.
        $json['recordscount'] = count($clientes->filas);
        // Cada uno de los clientes...
        foreach ($clientes->filas as $fila) {
            // lo creamos...
            $cliente = array(
                'id' => $fila->id,
                'nombre' => $fila->nombre,
                'email' => $fila->email
            );
            // y lo guardamos en el JSON
            array_push($json['records'], $cliente);
        }
        // Código de respuesta
        if ($json['recordscount'] == 0)
            http_response_code(204); // No Content
        else
            http_response_code(200); // OK
    } else {
        // Código de respuesta - 404 Not found
        http_response_code(404);
    }
    // Número de clientes seleccionados.
    if ($json['recordscount'] == 1)
        $json['message'] = "{$json['recordscount']} cliente seleccionado.";
    else
        $json['message'] = "{$json['recordscount']} clientes seleccionados.";
}
// Devolvemos el JSON
echo json_encode($json);
