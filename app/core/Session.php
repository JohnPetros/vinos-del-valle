<?php

namespace App\core;

class Session
{
  /**
   * Método responsável por iniciar a sessão
   */
  private static function init()
  {
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();

      if (!isset($_SESSION['previous_route'])) {
        $_SESSION['previous_route'] = '/';
      }
    }
  }

  /**
   * Método responsável por criar o sessão do usuário
   * @param User
   */
  public static function setUserSession($user)
  {
    self::init();

    $_SESSION['user'] = [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'is_admin' => $user->is_admin,
    ];
  }

  public static function setPreviousRoute($previousRoute)
  {
    self::init();

    $_SESSION['previous_route'] = $previousRoute;
  }


  public static function verifyLoggedUser($userType, $requirement, $request)
  {
    self::init();

    if ($requirement == 'login' && !isset($_SESSION['user']['id'])) {
      $request->getRoute()->redirect($_SESSION['previous_route']);
    }
    return;
  }
}
