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

    $user = Session::getUserSession();

    return View::render('partials/header', [
      'name' => $user['name'],
      'email' => $user['email'],
      'avatar' => $user['avatar'],
      'modal' => $modal,
      $page => 'active',
    ]);
  }
}
