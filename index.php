<?php 

ini_set('display_errors', 1);

require_once 'class/conection.php';

$conection = new Conection();

// $sql = "INSERT INTO pacientes (DNI) VALUE ('20')";

$sql = "INSERT INTO pacientes (DNI) VALUES ('2')";

var_dump($conection->nonQueryId($sql));
