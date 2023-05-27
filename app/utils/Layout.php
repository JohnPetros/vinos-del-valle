<?php

namespace App\utils;

use \App\core\View;
use \App\utils\Modal;

class Layout
{
  /**
   * Retorna o cabeçalho de Dashboard
   * @return string
   */
  public static function getDashboardHeader()
  {
    return View::render('partials/header', [
      'modal' => Modal::getModal(
        'sign-out',
        'Fazer logout',
        'Tem certeza que deseja sair da sua conta?',
        '/logout',
        'header'
      )
    ]);
  }
}
