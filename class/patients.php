<?php

declare(strict_types=1);

require_once 'conection.php';
require_once 'response.php';

class patients extends conection
{

    private string $table = 'pacientes';
    private $patientsId = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";


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

        // campos requridos
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
    }

    public function insertPatients() :int {
        $query = "INSERT INTO {$this->table} (DNI, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo)
        VALUES
        ('$this->dni', '$this->nombre', '$this->direccion', '$this->codigoPostal', '$this->telefono', '$this->genero', '$this->fechaNacimiento', '$this->correo')";

        $response = $this->nonQuery($query);

        if ($response) {
            return $response;
        } else {
            return 0;
        }
    }
}
