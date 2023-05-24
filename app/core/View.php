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
   * Define os variáveis comuns entra todas Views
   * @return array $vars
   */
  public static function init($vars = [])
  {
    self::$vars = $vars;
  }

  /**
   * Retorna o conteúdo de uma View
   * @param  string $view
   * @return string 
   */
  private static function getViewContent($viewName)
  {
    $file = __DIR__ . '/../views/' . $viewName . '.html';

    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * Retorna o conteúdo renderizado de uma View
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
