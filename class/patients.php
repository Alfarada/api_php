<?php

declare(strict_types=1);

require_once 'conection.php';
require_once 'response.php';

class patients extends conection
{

    private string $table = 'pacientes';

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
}
