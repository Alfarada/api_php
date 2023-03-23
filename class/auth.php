<?php

ini_set('display_errors', 1);

require_once 'conection.php';
require_once 'response.php';

class Auth extends Conection
{   
    // Este metodo recibe un json
    public function login($json)
    {
        $_response = new Response();
        // convertimos a un array asociativo
        $data = json_decode($json, TRUE);

        if (!isset($data['user']) || !isset($data['password'])) {
            // error en los campos
            return $_response->http_status_400();
        } else {
            // operacion exitosa
            $user = $data['user'];
            $password = $data['password'];
            $data = $this->getUserData($user); 


            if ($data) {
                // si existe el usuario
                echo "el usuario existe";
            } else {
                // no exite el usuario
                echo "el usuario no existe";
                return $_response->http_status_200("El usuario {$user} no existe");
            }

        }
    }

    private function getUserData($email)
    {
        $sql = "SELECT UsuarioId, Password, Estado FROM usuarios WHERE Usuario = '$email'";
        $data = parent::getData($sql);

        if (isset($data[0]['UsuarioId'])) {
            return $data;
        } else {
            return false;
        }

    }
}
