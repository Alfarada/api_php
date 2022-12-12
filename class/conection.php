<?php

class Conection
{

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conection;

    public function __construct() {
        
        $listing = $this->dataConection();

        foreach ($listing as $key => $value) {
            $this->server   = $value['$server'];
            $this->user     = $value['$user'];
            $this->password = $value['$password'];
            $this->database = $value['$database'];
            $this->port     = $value['$port'];
        }

        $this->connection = new mysqli(
            $this->server,
            $this->user, 
            $this->password, 
            $this->database, 
            $this->port
        );

        // true - have error
        // NULL - dont have error

        if ($this->conection->connect_errno) {
            echo 'ha fallado la conexion.';
            die;
        }

        echo 'conexion exitosa';
    }

    private function dataConection()
    {
        $direction = dirname(__FILE__);
        $json_data = file_get_contents($direction . "/" . "config");
        return json_decode($json_data, true);
    }
}
