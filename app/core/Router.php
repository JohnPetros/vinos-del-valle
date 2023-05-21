<?php

namespace App\core;

use \Exception;
use \Reflection;

class Router
{
  /**
   * URL completa da raiz do projeto
   * @var string
   */
  private $url = '';

  /**
   * Prefixo de todas as rotas
   * @var string
   */
  private $prefix = '';

  /**
   * Índice de rotas
   * @var array
   */
  private $routes = [];

  /**
   * Instância de Request
   * @var Request
   */
  private $request;


  /**
   * Método responsável por iniciar a classe
   * @var string $url
   */
  public function __construct($url)
  {
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
  }

  /**
   * Método responsável por definir o prefixo das rotas
   */
  public function setPrefix()
  {
    // INFORMAÇÕES DA URL ATUAL
    $parseUrl = parse_url($this->url);

    // DEFINE O PREFIXO
    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
   * Método responsável por adicionar uma rota na classe
   * @param string $method
   * @param string $route
   * @param array  $params
   */
  private function addRoute($method, $route, $controller)
  {
    // // VARIÁVEIS DA ROTA
    $variables = [];

    // PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
    $patternVariable = '/{(.*?)}/';
    if (preg_match_all($patternVariable, $route, $matches)) {
      $route = preg_replace($patternVariable, '(.*?)', $route);
      $variables = $matches[1];
    }

    // PADRÃO DE VALIDAÇÃO DA URL
    $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

    // ADICIONA A ROTA DENTRO DA CLASSE
    $this->routes[$patternRoute][$method]['controller'] = $controller;
    $this->routes[$patternRoute][$method]['variables'] = $variables;
  }

  /**
   * Método responsável por definir uma rota de GET
   * @param string $route
   * @param array  $params
   */
  public function get($route, $controller)
  {
    return $this->addRoute('GET', $route, $controller);
  }

  /**
   * Método responsável por definir uma rota de POST
   * @param string $route
   * @param array  $controller
   */
  public function post($route, $controller)
  {

    return $this->addRoute('POST', $route, $controller);
  }

  /**
   * Método responsável por definir uma rota de PUT
   * @param string $route
   * @param array  $controller
   */
  public function put($route, $controller)
  {
    return $this->addRoute('PUT', $route, $controller);
  }

  /**
   * Método responsável por definir uma rota de DELETE
   * @param string $route
   * @param array  $controller
   */
  public function delete($route, $controller)
  {
    return $this->addRoute('DELETE', $route, $controller);
  }

  /**
   * Método responsável por retornar a URI desconsiderando o prefixo
   * @return string 
   */
  private function getUri()
  {
    // URI DA REQUEST
    $uri = $this->request->getUri();

    // FATIA A URI COM O PREFIXO
    $uri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    // RETORNA URI SEM PREFIXO
    return end($uri);
  }

  /**
   * Método responsável por retornar os dados da rota atual
   * @return array 
   */
  private function getRoute()
  {
    // URI
    $uri = $this->getUri();

    // MÉTODO HTTP
    $httpMethod = $this->request->getHttpMethod();

    // VALIDA AS ROTAS
    foreach ($this->routes as $patternRoute => $methods) {
      // VERIFICA SE A URI BATE COM O PADRÃO
      if (preg_match($patternRoute, $uri, $matches)) {

        // VERIFICA O MÉTODO
        if (isset($methods[$httpMethod])) {
          unset($matches[0]);

          // VARIÁVEIS PROCESSADAS
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          // RETORNO DOS DADOS DA ROTA
          return $methods[$httpMethod];
        }
        // MÉTODO NÃO PERMITIDO/DEFINIDO
        throw new Exception("Método HTTP   não é permitido", 405);
      }
    }
    // URL NÃO ENCONTRADA 
    throw new Exception("URL não encontrada", 404);
  }

  /**
   * Método responsável por executar a rota atual
   * @return Response
   */
  public function run()
  {
    try {
      // // OBTÉM A ROTA ATUAL
      $route = $this->getRoute();

      // VERIFICA O CONTROLLER
      if (!isset($route['controller'])) {
        throw new Exception("A URL não pôde ser processada", 500);
      }

      // ARGUMENTOS DA FUNÇÃO DO CONTROLLER
      $args = [];

      // // REFLECTION
      // $reflection = new ReflectionFunction($route['controller']);

      // foreach ($reflection->getParameters() as $parameter) {
      //   $name = $parameter->getName();
      //   $args[$name] = $route['variables'][$name] ?? '';
      // }

      // RETORNA A EXECUÇÃO DA FUNÇÃO DO CONTROLLER
      return call_user_func_array($route['controller'], $args);
    } catch (Exception $error) {
      return new Response($error->getCode(), $error->getMessage());
    }
  }
}
