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
            $password = $this->encrypt($password);
            $data = $this->getUserData($user); 


            if ($data) {
                // si existe el usuario
                // comprobar que las contraseñas coinciden

                // var_dump($password, $data[0]['Password'] );
                if ($password === $data[0]['Password']) {
                    // las contraseñas son iguales
                    // comprobamos que el usuario está activo

                    if ($data[0]['Estado'] === 'Activo') {
                        // crear token        
                        $verify = $this->insertToken($data[0]['UsuarioId']);

                        if ($verify) {
                            // se guardó
                            $result = $_response->response;
                            $result['result'] = [ "token" => $verify ];
                            return $result;

                        } else {
                            // error al guardar
                            return $_response->http_status_500("Error interno, no se pudo guardar");
                        }
                        
                    } else {
                        // usuario inactivo 
                        return $_response->http_status_200("El usuario está inactivo");
                    }

                } else {
                    // las contraseñas no coinciden 
                    return $_response->http_status_200("Contraseña invalida");
                }
                
            } else {
                // no exite el usuario
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

    private function insertToken($id) {
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i");
        $status = 'Activo';
        $sql = "INSERT INTO usuarios_token (UsuarioId, token, Estado, fecha) VALUES ('$id', '$token', '$status', '$date')";
        $verify = (bool) $this->nonQuery($sql);

        if ($verify) {
            return $token;
        } else {
            return false;
        }
    }
}
