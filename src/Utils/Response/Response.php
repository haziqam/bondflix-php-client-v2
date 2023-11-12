<?php

namespace Utils\Response;

class Response
{
    public $success;
    public $statusCode;
    public $message;
    public $data;

    public function __construct($status, $statusCode, $message, $data){
        $this->success = $status;
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    public function encode_to_JSON() {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        echo json_encode($this);
    }
}