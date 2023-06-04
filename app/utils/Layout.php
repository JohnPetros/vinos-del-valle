<?php

namespace App\utils;

use App\controllers\UserController;
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
    $avatar =  UserController::verifyAvatarExists($user['avatar'])
      ? $user['avatar']
      : 'default.png';

    return View::render('partials/header', [
      'name' => $user['name'],
      'email' => $user['email'],
      'avatar' => $avatar,
      'modal' => $modal,
      $page => 'active',
    ]);
  }
}
