<?php

namespace App\core;

class View
{

  /**
   * Variáveis padrões da View
   * @var array
   */
  public static $vars = [];

  /**
   * Método responsável por definir os dados iniciais da classe
   * @return array $vars
   */
  public static function init($vars = [])
  {
    self::$vars = $vars;
  }

  /**
   * Método responsável por retornar o conteúdo de uma view
   * @param  string $view
   * @return string 
   */
  private static function getViewContent($viewName)
  {
    $file = __DIR__ . '/../views/' . $viewName . '.html';

    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * Método responsável por retornar o conteúdo renderizado de uma View
   * @param  string $view
   * @param  array  $vars (string/numeric)
   * @return string
   */
  public static function render($viewName, $vars = [])
  {
    $viewContent = self::getViewContent($viewName);

    $vars = array_merge(self::$vars, $vars);

    $keys = array_keys($vars);

    $keys = array_map(function ($key) {
      return '{{' . $key . '}}';
    }, $keys);

    return str_replace($keys, array_values($vars), $viewContent);
  }
}
