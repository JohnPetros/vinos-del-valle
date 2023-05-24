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
   * Cria o sessão do usuário
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

  public static function isUserLogged()
  {
    return isset($_SESSION['user']['id']);
  }

  public static function isUserAdmin()
  {
    return isset($_SESSION['user']['is_admin']);
  }


  /**
   * 
   */
  public static function verifyLoggedUser($requirement, $routerType, $request)
  {
    self::init();

    if (
      ($requirement == 'login' && !self::isUserLogged()) ||
      ($requirement == 'logout' && self::isUserLogged()) ||
      ($routerType == 'admin' && !self::isUserAdmin()) ||
      ($routerType == 'default' && self::isUserAdmin())
    ) {
      $request->getRouter()->redirect($_SESSION['previous_route']);
    }

    self::setPreviousRoute($request->getUri());
  }
}
