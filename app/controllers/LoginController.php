<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use App\models\User;
use App\utils\Toast;

class LoginController
{

  /**
   * Retorna o conteúdo (View) da página de login
   * @param Request $request
   * @param string $errorMessage
   * @return string
   */
  public static function getLoginPage($request, $errorMessage = null)
  {
    Session::verifyLoggedUser('logout', null, $request);

    $logo = View::render('partials/logo');
    $status = !is_null($errorMessage) ? Toast::getError($errorMessage) : '';

    return View::render('pages/login', [
      'status' => $status,
      'logo' => $logo,
    ]);
  }

  /**
   * Lida com a tentativa de Login do usuário
   * @param Request $request
   * @return string
   */
  public static function handleLogin($request)
  {
    $postVars = $request->getPostVars();
    $email = $postVars['email'] ?? '';
    $password = $postVars['password'] ?? '';

    $user = User::getUserByEmail($email);

    if (!$user instanceof User || !password_verify($password, $user->password)) {
      return self::getLoginPage($request, 'Usuário não encontrado');
    }

    Session::setUserSession($user);

    $route = $user->is_admin ? '/dashboard/wine' : '/app';

    $request->getRouter()->redirect($route);
  }

  /**
   * Lida com a tentativa de Logout do usuário
   * @param Request $request
   * @return string
   */
  public static function handleLogout($request)
  {
    Session::logout();
    $request->getRouter()->redirect('/');
  }
}
