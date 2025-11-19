<?php

namespace App\Http;
use \Closure;
use \Exception;
use \ReflectionFunction;

class Router {

    // URL completa do projeto
    private $url = '';

    // Define o que é comum em todas as rotas
    private $prefix = '';

    // Armazena as rotas / Indice de rotas
    private  $routes = [];

    // Instância de Request
    private $request;

    // Método responsável por iniciar a classe
    public function __construct($url) {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    // Método responsável por definir o prefixo das rotas
    private function setPrefix() {
        // Informações da URL Atual
        $parseUrl = parse_url($this->url);
        
        // Define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    // Método repsonsável por adicionar uma rota na classe
    private function addRoute($method, $route, $params = []) {
        
        // Validação dos parâmetros
        foreach ($params as $key=>$value) {
            if($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // Variáveis da Rota
        $params['variables'] = [];

        // Padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
                $route = preg_replace($patternVariable, '(.*?)', $route);
                $params['variables'] = $matches[1];
        }

        //Padrão de validação da URL.
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        // Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    // Método responsável por definir uma rota de GET
    public function get($route, $params = []) {
        return $this->addRoute('GET', $route, $params);
    }

    // Método responsável por definir uma rota de POST
    public function post($route, $params = []) {
        return $this->addRoute('POST', $route, $params);
    }

    // Método responsável por definir uma rota de PUT
    public function put($route, $params = []) {
        return $this->addRoute('PUT', $route, $params);
    }

    // Método responsável por definir uma rota de DELETE
    public function delete($route, $params = []) {
        return $this->addRoute('DELETE', $route, $params);
    }

    // Método repsonsável por retornar a URI desconsiderando o prefixo
    private function getUri() {
        // URI da Request
        $uri = $this->request->getUri();
        
        // Fatia a URI com prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        
        // Retorna a URI sem o prefixo
        return end($xUri);
    }

    // Método repsonsável por retornar os dados da rota atual
    private function getRoute() {
        // URI
        $uri = $this->getUri();

        // Method
        $httpMethod = $this->request->getHttpMethod();

        // Valida as rotas
        foreach($this->routes as $patternRoute=>$methods) {
            // Verifica se a rota bate com o padrão
            if(preg_match($patternRoute, $uri, $matches)) {
                // verifica o método
                if(isset($methods[$httpMethod])) {
                    // Remove a primeir aposição
                    unset($matches[0]);

                    // Variáveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // Retorno dos parametros da rota
                    return $methods[$httpMethod];
                }

                // Método não permitido
                throw new Exception("Método não permitido", 405);
            }
        }

        //URL não encontrada
        throw new Exception("URL não encontrada", 404);
    }

    // Método responsável por executar a rota atual
    public function run() {
        try {
            // Obtem a rota atual
            $route = $this->getRoute();

            // Verifica o controlador
            if(!isset($route['controller'])) {
                throw new Exception("A URL não pode ser processada", 500);
            }

            // Argumentos da função
            $args = [];

            // Reflection
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? null;
            }

            // Retorna a execução da função
            return call_user_func_array($route['controller'], $args);
            
        } catch(Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

}


/*  debug
    echo "<pre>";
    print_r($route);
    echo "</pre>";
    exit; 
            */