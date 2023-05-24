<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use App\models\User;
use App\utils\Toast;

class LoginController
{

  /**
   * Método responsável por retornar o conteúdo (View) da página de login
   * @param string $errorMessage
   * @return string
   */
  public static function getLoginPage($request, $errorMessage = null)
  {

    $status = !is_null($errorMessage) ? Toast::getError($errorMessage) : '';
    return View::render('pages/login', [
      'status' => $status,
    ]);
  }

  /**
   * Método responsável por definir o Login do usuário
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

    $page = $user->is_admin ? '/dashboard' : '/app';

    $request->getRouter()->redirect($page);
  }
}
