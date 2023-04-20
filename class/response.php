<?php

class Response {

    public array $response = [
        "status" => "ok",
        "result" => []
    ];

    public function http_status_405() :array {
        $this->response['status'] = 'error';
        $this->response['result'] = [
            "error_id" => "405",
            "error_msg" => "Method not allowed"
        ];

        return $this->response;
    }
    
    public function http_status_400() :array {
        $this->response['status'] = 'error';
        $this->response['result'] = [
            "error_id" => "400",
            "error_msg" => "incomplete data or incorrect format"
        ];

        return $this->response;
    }
    
    public function http_status_200(string $message = "incorrect data") :array {
        $this->response['status'] = 'error';
        $this->response['result'] = [
            "error_id" => "200",
            "error_msg" => $message
        ];

        return $this->response;
    }

    public function http_status_500(string $message = "Error interno del servidor") :array {
        $this->response['status'] = 'error';
        $this->response['result'] = [
            "error_id" => "500",
            "error_msg" => $message
        ];

        return $this->response;
    }


    public function http_status_401(string $message = "No autorizado") :array {
        $this->response['status'] = 'error';
        $this->response['result'] = [
            "error_id" => "401",
            "error_msg" => $message
        ];

        return $this->response;
    }
}