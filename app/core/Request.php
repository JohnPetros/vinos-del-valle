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
   * Arquivos recebidas do POST da página ($_FILES)
   * @var array
   */
  private $files = [];

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
    $this->files = $_FILES ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->setUri();
  }

  /**
   * Refinir a URI
   */
  private function setUri()
  {
    // URI COMPLETA (COM GETS)
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    $uri = explode('?', $this->uri);
    $this->uri = $uri[0];
  }

  /**
   * Retorna a instância de Router
   * @return Router
   */
  public function getRouter()
  {
    return $this->router;
  }

  /**
   * Retorna o método HTTP da requisição
   * @return string
   */
  public function getHttpMethod()
  {
    return $this->httpMethod;
  }

  /**
   * Retorna a URI da requisição
   * @return string
   */
  public function getUri()
  {
    return $this->uri;
  }

  /**
   * Retorna o cabeçalho da requisição
   * @return array
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * Retorna os parâmetros da requisição
   * @return array
   */
  public function getQueryParams()
  {
    return $this->queryParams;
  }

  /**
   * Retorna as variáveis de POST da requisição
   * @return array
   */
  public function getPostVars()
  {
    return $this->postVars;
  }

  /**
   * Retorna as arquivos enviados por POST da requisição
   * @return array
   */
  public function getFiles()
  {
    return $this->files;
  }
}
