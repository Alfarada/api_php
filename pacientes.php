<?php 

declare(strict_types=1);

require_once 'class/response.php';
require_once 'class/patients.php';

$_response = new response();
$_patients = new patients();



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // echo 'hola get';
    if (isset($_GET['page'])) {
        $page = (int) $_GET['page'];
        $list = $_patients->patientsList($page);
        echo json_encode($list);
    } else if (isset($_GET['id'])){
        $patient_id = $_GET['id'];
        $patient_data = $_patients->getPatient($patient_id);
        echo "<pre>" . json_encode($patient_data) . "</pre>";
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo 'hola post';
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    echo 'hola put';
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    echo 'hola delete';
} else {
    header('Content-type: application/json');
    $data_array = $_response->http_status_405();
    echo json_encode($data_array);
}