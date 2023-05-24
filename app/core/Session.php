<?php

namespace App\core;

class Session
{
  /**
   * Inicia a sessão
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

  /**
   * Define a rota anterior acessada pelo usuário
   */
  public static function setPreviousRoute($previousRoute)
  {
    $_SESSION['previous_route'] = $previousRoute;
  }

  /**
   * Verifica se o usuário está logado
   * @return boolean
   */
  public static function isUserLogged()
  {
    return isset($_SESSION['user']['id']);
  }

  /**
   * Verifica se o usuário é admin
   * @return boolean
   */
  public static function isUserAdmin()
  {
    return isset($_SESSION['user']['is_admin']);
  }

  /**
   * Redireciona para rota anterior dependendo do caso se o usuário está logado ou não, bem como o tipo rota que está sendo acessada
   * @param 
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

  /**
   * Método responsável por executar o logout do usuário
   * @return boolean
   */
  public static function logout()
  {
    self::init();
    unset($_SESSION['user']);
  }
}
