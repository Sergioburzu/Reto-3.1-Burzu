<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once('../bd/clientes.php');

//cambio1  cambio sintaxis corta de array [] en lugar de array()
$json = []; 

if ($_SERVER['REQUEST_METHOD'] != "DELETE") {
    http_response_code(405);
    $json['message'] = 'Sólo se permite el método DELETE.';
    // cambio 2 finalizo el script aqui para evitar anidamiento
    echo json_encode($json);
    exit;
}

//Cambio 3 Uso de !empty() en lugar de isset()
if (!empty($_GET['id'])) {
    $cliente = new ClientesBD();
    
    //Cambio 4 casting a entero para que el ID sea un número seguro
    $cliente->id = (int)$_GET['id']; 
    
    $filas = $cliente->Borrar();

    if ($filas == -1 || $cliente->Error()) {
        http_response_code(400);
        $json['message'] = $cliente->GetError();
    } elseif ($filas == 0) {
        http_response_code(400);
        $json['message'] = 'Cliente no borrado.';
    } else {
        http_response_code(200);
        $json['message'] = 'Cliente borrado.';
    }
} else {
    http_response_code(400);
    $json['message'] = 'No se indica el cliente a borrar.';
}

echo json_encode($json);