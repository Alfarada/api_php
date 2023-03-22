<?php

class Conection {

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conection;

    public function __construct() {
        
        $listing = $this->dataConection();

        foreach ($listing as $key => $value) {
            $this->server   = $value['server'];
            $this->user     = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port     = $value['port'];
        }

        $this->conection = new mysqli(
            $this->server,
            $this->user, 
            $this->password, 
            $this->database, 
            $this->port
        );

        //  have error      - int (1049)
        //  dont have error - int (0)

        if ($this->conection->connect_errno) {
            die('Connect Error: ' . $this->conection->connect_errno);
        }

        echo 'conexion exitosa';
    }

    private function dataConection() {

        $direction = dirname(__FILE__);
        $json_data = file_get_contents($direction . "/" . "config");
        return json_decode($json_data, true);
    }

    private function convertUTF8(array $array) :array {
        
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, "utf-8", true)) {
                $item = utf8_decode($item);
            }
        });

        return $array;
    }

    public function getData(string $sql) {
        $results = $this->conection->query($sql);
        $resultArr = [];
        foreach ($results as $key) {
            $resultArr[] = $key;
        }

        return $this->convertUTF8($resultArr);
    }


    // retorna 1 si una fila ha sido afectada
    // retorna -1 si no se ha afectado ninguna fila
    public function nonQuery(string $sql) :int {
        $results = $this->conection->query($sql);
        return $this->conection->affected_rows;
    }
    
    // retorna el id 
    public function nonQueryId(string $sql) {
        $results = $this->conection->query($sql);
        $rows = $this->conection->affected_rows;

        if ($rows >= 1) {
            return $this->conection->insert_id;
        } else {
            return 0;
        }
    }
}
