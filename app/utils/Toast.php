<?php

namespace App\utils;

use App\core\View;

class Toast
{
  /**
   * Método responsável por retornar uma mensagem de sucesso
   * @param string $message
   * @return string
   */
  public static function getSuccess($message)
  {
    return View::render('partials/toast', [
      'type' => 'success',
      'title' => 'Sucesso',
      'icon' => 'check',
      'message' => $message
    ]);
  }

  /**
   * Método responsável por retornar uma mensagem de sucesso
   * @param string $message
   * @return string
   */
  public static function getError($message)
  {
    return View::render('partials/toast', [
      'type' => 'error',
      'title' => 'Erro',
      'icon' => 'x',
      'message' => $message
    ]);
  }
}
