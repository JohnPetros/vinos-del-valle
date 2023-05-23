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

  /**
   * Método responsável por verificar se o usuário está logado
   * @return boolean
   */
  public static function isUserLogged()
  {
    self::init();

    return isset($_SESSION['user']['id']);
  }
}
