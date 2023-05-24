<?php

namespace App\core;

class Environment
{
  /**
   * Carregar as variáveis de ambiente do projeto
   * @param string $dir Caminho absoluto da pasta onde encontra-se o arquivo .env
   */
  public static function load($dir)
  {
    if (!file_exists($dir . '/.env')) {
      return;
    }

    $lines = file($dir . '/.env');
    foreach ($lines as $line) {
      putenv(trim($line));
    }
  }
}
