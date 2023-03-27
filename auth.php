<?php 

require_once 'class/auth.php';
require_once 'class/response.php';

$_auth = new Auth();
$_response = new Response();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // recibir datos
    $post_json = file_get_contents("php://input");

    // enviando datos al manejador
    $array_data = $_auth->login($post_json);

    //devolvemos una respuesta 
    header('Content-type: application/json');

    if (isset($array_data['result']['error_id'])) {
        $response_code = $array_data['result']['error_id'];
        http_response_code($response_code);
    } else {
        http_response_code(200);
    }

    // solo para depurar
    // print_r(json_encode($array_data));

    echo json_encode($array_data);
} else {

    header('Content-type: application/json');
    $data_array = $_response->http_status_405();
    echo json_encode($data_array);  
}

