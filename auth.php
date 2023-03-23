<?php 

ini_set('display_errors', 1);

require_once 'class/auth.php';
require_once 'class/response.php';

$_auth = new Auth();
$_response = new Response();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $post_json = file_get_contents("php://input");
    $array_data = $_auth->login($post_json);
    print_r(json_encode($array_data));
} else {
    echo 'Metodo no permitido';
}

