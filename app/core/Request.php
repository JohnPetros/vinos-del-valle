<?php

namespace App\core;

class Request
{
  /**
   * Instância do Router
   * @var Router
   */
  private $router;

  /**
   * Método HTTP da requisição
   * @var string
   */
  private $httpMethod;

  /**
   * URI da página
   * @var string
   */
  private $uri;

  /**
   * Parâmetros da URL ($_GET)
   * @var array
   */
  private $queryParams = [];

  /** 
   * Variáveis recebidas do POST da página ($_POST)
   * @var array
   */
  private $postVars = [];

  /** 
   * Cabeçalho da requisição
   * @var array
   */
  private $headers = [];

  /**
   * Construtor da classe
   */
  public function __construct($router)
  {
    $this->router = $router;
    $this->queryParams = $_GET ?? [];
    $this->postVars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->setUri();
  }

  /**
   * Método responsável por definir a URI
   */
  private function setUri()
  {
    // URI COMPLETA (COM GETS)
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    $uri = explode('?', $this->uri);
    $this->uri = $uri[0];
  }

  /**
   * Método responsável por retornar a instância de Router
   * @return Router
   */
  public function getRouter()
  {
    return $this->router;
  }

  /**
   * Método responsável por retornar o método HTTP da requisição
   * @return string
   */
  public function getHttpMethod()
  {
    return $this->httpMethod;
  }

  /**
   * Método responsável por retornar a URI da requisição
   * @return string
   */
  public function getUri()
  {
    return $this->uri;
  }

  /**
   * Método responsável por retornar o cabeçalho da requisição
   * @return array
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * Método responsável por retornar os parâmetros da requisição
   * @return array
   */
  public function getQueryParams()
  {
    return $this->queryParams;
  }

  /**
   * Método responsável por retornar as variáveis POST da requisição
   * @return array
   */
  public function getPostVars()
  {
    return $this->postVars;
  }
}
