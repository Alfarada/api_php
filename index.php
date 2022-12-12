<?php 

ini_set('display_errors', 1);

require_once 'class/conection.php';

$conection = new Conection();

$sql = "SELECT * FROM pacientes";

print_r($conection->getData($sql));