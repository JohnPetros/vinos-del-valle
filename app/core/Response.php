<?php

namespace App\core;

class Response
{
  /**
   * Código de status HTTP
   * @var integer
   */
  private $httpCode = 200;

  /**
   * Cabeçalho da resposta
   * @var array
   */
  private $headers = [];

  /**
   * Tipo de conteúdo que está sendo retornado
   * @var string
   */
  private $contentType = 'text/html';

  /**
   * Conteúdo da resposta
   * @var mixed
   */
  private $content;

  /**
   * Inicia a classe e define os valores da resposta do servidor
   * @param integer $httpcode
   * @param mixed   $content
   * @param string  $contentType
   */
  public function __construct($httpCode, $content, $contentType = 'text/html')
  {
    $this->httpCode = $httpCode;
    $this->content = $content;
    $this->setContentType($contentType);
  }

  /**
   * Altera o tipo de conteúdo da resposta do servidor
   * @param string $contentType
   */
  public function setContentType($contentType)
  {
    $this->contentType = $contentType;
    $this->addHeader('Content-Type', $contentType);
  }

  /**
   * Adiciona um registro no cabeçalho na resposta
   * @param string $key
   * @param string $value
   */
  public function addHeader($key, $value)
  {
    $this->headers[$key] = $value;
  }

  /**
   * Envia o cabeçalho para o navegador
   */
  public function sendHeaders()
  {
    http_response_code($this->httpCode);

    foreach ($this->headers as $key => $value) {
      header($key . ': ' . $value);
    }
  }

  /**
   * Envia a resposta para o usuário
   */
  public function sendResponse()
  {
    $this->sendHeaders();

    switch ($this->contentType) {
      case 'text/html':
        echo $this->content;
        exit;
    }
  }
}
