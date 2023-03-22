<?php 

ini_set('display_errors', 1);

require_once 'class/conection.php';

$conection = new Conection();

// $sql = "INSERT INTO pacientes (DNI) VALUE ('20')";

$sql = "SELECT * FROM pacientes";

// print_r($conection->nonQueryId($sql));

var_dump($conection->getData($sql));