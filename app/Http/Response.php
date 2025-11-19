<?php

namespace App\Http;

class Response {

    // Código do status HTTP
    private $httpCode = 200;

    // Headers do response
    private $headers = [];

    // Tipo de conteúdo que está sendo retornado
    private $contentType = 'text/html';

    // Conteúdo do response
    private $content;

    // Método responsável por iniciar a classe e definir os valores
    public function __construct($httpCode, $content, $contentType = 'text/html') {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    // Método responsável por alterar o content type do response
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    // Método responsável por adicionar um registro no headers do response
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }

    // Método responsável por enviar os headers para o navegador
    private function sendHeaders() {
        // Status
        http_response_code($this->httpCode);
        // Enviar headers
        foreach ($this->headers as $key => $value) {
            header($key . ": " . $value);
        }
    }

    // Método responsável por enviar a resposta ao usuário
    public function sendReponse() {
        // Envia os headers
        $this -> sendHeaders();
        // Imprime o conteúdo
        switch($this->contentType) {
            case 'text/html' :
                echo $this->content;
                exit;
        }
    }
}