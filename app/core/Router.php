<?php

namespace App\core;

class Router
{
  /**
   * URL completa da raiz do projeto
   * @var string
   */
  private $url = '';

  /**
   * Prefixo de todas rotas
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
}
