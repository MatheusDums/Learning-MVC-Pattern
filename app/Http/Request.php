<?php

namespace App\Http;

class Request
{

    // Método HTTP da requisição
    private $httpMethod;

    // URI da página
    private $uri;

    // Parâmetros da URL
    private $queryParams = [];

    // Variáveis recebidas no POST da página
    private $postVars  = [];

    // Cabeçalhos da requisição
    private $headers = [];

    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    }

    // Retorna o método HTTP da requisição
    public function getHttpMethod() {
        return $this->httpMethod;
    }

    // Retorna o URI da requisição
    public function getUri() {
        return $this->uri;
    }

    // Retorna os headers da requisição
    public function getHeaders() {
        return $this->headers;
    }
    
    // Retorna os parametros da url da requisição
    public function getQueryparams() {
        return $this->queryParams;
    }
    
    // Retorna as variaveis POST da requisição
    public function getPostVars() {
        return $this->postVars;
    }
}
