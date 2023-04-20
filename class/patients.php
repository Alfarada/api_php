<?php

declare(strict_types=1);

require_once 'conection.php';
require_once 'response.php';

class patients extends conection
{

    private string $table = 'pacientes';
    private $pacienteId = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    // private $fechaNacimiento = "0000-00-00";
    private $fechaNacimiento = NULL;
    private $correo = "";
    private $token = "";

    public function patientsList(int $page = 0): array
    {
        $start = 0;
        $cuantity = 100;

        if ($page > 1) {
            $start = ($cuantity * ($page - 1)) + 1;
            $cuantity = $cuantity * $page;
        }

        $sql = "SELECT PacienteId, Nombre, DNI, Telefono, Correo FROM $this->table limit $start, $cuantity";

        return $this->getData($sql);
    }

    public function getPatient($id)
    {
        $sql = "SELECT * FROM $this->table WHERE PacienteId = '$id'";
        return $this->getData($sql);
    }

    /**
     *  metodo post
     * 
     * Este manejador valida los datos requeridos
     * y luego se hace un insert a la base de datos
     */
    public function post($json)
    {
        $_response = new Response();
        // convertimos a matriz asociativa
        $data = json_decode($json, true);

        // preguntar si hay un token
        if (!isset($data['token'])) {

            return $_response->http_status_401();
        } else {
            $this->token = $data['token'];
            $array_token = $this->searchToken();

            if ($array_token) {
                // hacemos el post    
                // validando campos
                if (!isset($data['nombre']) || !isset($data['dni']) || !isset($data['correo'])) {
                    return $_response->http_status_400();
                } else {

                    // no es necesario validar
                    $this->nombre = $data['nombre'];
                    $this->dni = $data['dni'];
                    $this->correo = $data['correo'];

                    // validando campos 
                    if (isset($data['telefono'])) {
                        $this->telefono = $data['telefono'];
                    }
                    if (isset($data['direccion'])) {
                        $this->direccion = $data['direccion'];
                    }
                    if (isset($data['codigoPostal'])) {
                        $this->codigoPostal = $data['codigoPostal'];
                    }
                    if (isset($data['genero'])) {
                        $this->genero = $data['genero'];
                    }
                    if (isset($data['fechaNacimiento'])) {
                        $this->fechaNacimiento = $data['fechaNacimiento'];
                    }

                    $insertId = $this->insertPatients();
                    // var_dump($insertId);

                    if ($insertId) {
                        $response = $_response->response;
                        $response['result'] = [
                            "pacienteId" => $insertId,
                        ];

                        return $response;
                    } else {

                        return $_response->http_status_500();
                    }
                }
            } else {
                // respuesta de error
                return $_response->http_status_401('token invalido o caducado.');
            }
        }
    }

    public function insertPatients(): int
    {
        $query = "INSERT INTO {$this->table} (DNI, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo)
        VALUES
        ('$this->dni', '$this->nombre', '$this->direccion', '$this->codigoPostal', '$this->telefono', '$this->genero', NULL, '$this->correo')";

        // var_dump($query);
        
        $response = $this->nonQuery($query);
        
        // var_dump($response);

        if ($response >= 1) {
            return $response;
        } else {
            return 0;
        }
    }

    public function put($json)
    {
        $_response = new Response();
        $data = json_decode($json, true);

        var_dump($data);

        // preguntar si hay un token
        if (!isset($data['token'])) {

            return $_response->http_status_401();
        } else {
            $this->token = $data['token'];
            $array_token = $this->searchToken();

            if ($array_token) {
                // hacemos el put
                if (!isset($data['pacienteId'])) {
                    return $_response->http_status_400();
                } else {

                    $this->pacienteId = $data['pacienteId'];

                    if (isset($data['nombre'])) {
                        $this->nombre = $data['nombre'];
                    }
                    if (isset($data['dni'])) {
                        $this->dni = $data['dni'];
                    }
                    if (isset($data['correo'])) {
                        $this->correo = $data['correo'];
                    }
                    if (isset($data['telefono'])) {
                        $this->telefono = $data['telefono'];
                    }
                    if (isset($data['direccion'])) {
                        $this->direccion = $data['direccion'];
                    }
                    if (isset($data['codigoPostal'])) {
                        $this->codigoPostal = $data['codigoPostal'];
                    }
                    if (isset($data['genero'])) {
                        $this->genero = $data['genero'];
                    }
                    if (isset($data['fechaNacimiento'])) {
                        $this->fechaNacimiento = $data['fechaNacimiento'];
                    }

                    $insertId = $this->modifyPatients();

                    var_dump($insertId);

                    if ($insertId) {
                        $response = $_response->response;
                        $response['result'] = [
                            "pacienteId" => $this->pacienteId,
                        ];

                        return $response;
                    } else {

                        return $_response->http_status_500();
                    }
                }
            } else {
                // respuesta de error
                return $_response->http_status_401('token invalido o caducado.');
            }
        }
    }

    public function modifyPatients()
    {
        $query = " UPDATE " . $this->table . " SET Nombre = '" . $this->nombre . "', Direccion = '" . $this->direccion . "', DNI = '" . $this->dni . "', CodigoPostal = '" . $this->codigoPostal . "', Telefono = '" . $this->telefono . "', Genero = '" . $this->genero . "', FechaNacimiento = NULL, Correo = '" . $this->correo . "' WHERE pacienteId = '" . $this->pacienteId . "'";

        // var_dump($query);
        // exit;

        $response = $this->nonQuery($query);

        if ($response >= 1) {
            return $response;
        } else {
            return 0;
        }
    }

    public function delete($json)
    {
        $_response = new Response();
        $data = json_decode($json, true);

        // preguntar si hay un token
        if (!isset($data['token'])) {

            return $_response->http_status_401();
        } else {
            $this->token = $data['token'];
            $array_token = $this->searchToken();

            if ($array_token) {
                // hacemos el post
                if (!isset($data['pacienteId'])) {
                    return $_response->http_status_400();
                } else {

                    // var_dump($data['pacienteId']);

                    $this->pacienteId = $data['pacienteId'];

                    $deleteId = $this->deleteUser();

                    if ($deleteId) {
                        $response = $_response->response;
                        $response['result'] = [
                            "pacienteId" => $this->pacienteId,
                        ];

                        return $response;
                    } else {

                        return $_response->http_status_500();
                    }
                }
            } else {
                // respuesta de error
                return $_response->http_status_401('token invalido o caducado.');
            }
        }
    }

    private function deleteUser()
    {
        $sql = " DELETE FROM " . $this->table . " WHERE PacienteId = '" . $this->pacienteId . "'";

        $resp = parent::nonQuery($sql);

        return ($resp >= 1) ? $resp : 0;
    }

    private function searchToken()
    {
        $sql = "SELECT TokenId, UsuarioId, Estado FROM usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = $this->getData($sql);

        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }

    // este metodo es para verifiar si el token sigue estando activo

    private function updateToken($tokenId)
    {
        $date = date("Y-m-d H:i");
        $sql = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenId'";
        $resp = $this->nonQuery($sql);

        return ($resp >= 1) ? $resp : 0;
    }
}
