<?php

declare(strict_types=1);

require_once 'class/response.php';
require_once 'class/patients.php';

$_response = new response();
$_patients = new patients();



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['page'])) {
        $page = (int) $_GET['page'];
        $list = $_patients->patientsList($page);
        header('Content-type: application/json');
        echo json_encode($list);
        http_response_code(200);
    } else if (isset($_GET['id'])) {
        $patient_id = $_GET['id'];
        $patient_data = $_patients->getPatient($patient_id);
        header('Content-type: application/json');
        echo json_encode($patient_data);
        http_response_code(200);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // recibimos los datos 
    $post_body = file_get_contents("php://input");
    // manejador
    $array_data = $_patients->post($post_body);

    // print_r($response);

    //devolvemos una respuesta 
    header('Content-type: application/json');

    if (isset($array_data['result']['error_id'])) {
        $response_code = $array_data['result']['error_id'];
        http_response_code($response_code);
    } else {
        http_response_code(200);
    }

    echo json_encode($array_data);

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    
    // recibimos los datos 
    $post_body = file_get_contents("php://input");

    // enviamos datos al manejador
    $array_data =  $_patients->put($post_body);
    
    //devolvemos una respuesta 
    header('Content-type: application/json');

    if (isset($array_data['result']['error_id'])) {
        $response_code = $array_data['result']['error_id'];
        http_response_code($response_code);
    } else {
        http_response_code(200);
    }

    echo json_encode($array_data);

} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // recibimos los datos 
    $post_body = file_get_contents("php://input");
    // enviamos datos al manejador
    $array_data =  $_patients->delete($post_body);

    //devolvemos una respuesta 
    header('Content-type: application/json');

    if (isset($array_data['result']['error_id'])) {
        $response_code = $array_data['result']['error_id'];
        http_response_code($response_code);
    } else {
        http_response_code(200);
    }

    echo json_encode($array_data);

} else {
    header('Content-type: application/json');
    $data_array = $_response->http_status_405();
    echo json_encode($data_array);
}
