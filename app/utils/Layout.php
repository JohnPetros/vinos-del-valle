<?php

namespace App\utils;

use App\core\Session;
use \App\core\View;
use \App\utils\Modal;

class Layout
{
  /**
   * Retorna o cabeçalho de Dashboard
   * @return string
   */
  public static function getDashboardHeader($page)
  {

    $modal = Modal::getModal(
      'sign-out',
      'Fazer logout',
      'Tem certeza que deseja sair da sua conta?',
      '/logout',
      'logout',
    );

    return View::render('partials/header', [
      'name' => Session::getUserSession()['name'],
      'email' => Session::getUserSession()['email'],
      'modal' => $modal,
      $page => 'active',
    ]);
  }
}
