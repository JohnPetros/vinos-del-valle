<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use App\models\User;
use App\utils\Form;
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
    $toast =  !empty($errorMessage) ? Toast::getError($errorMessage) : '';

    return View::render('pages/login', [
      'toast' => $toast,
      'logo' => $logo,
    ]);
  }

   /**
   * Verifica se um usuário com um dado E-mail já existe
   * @param string $email 
   * @return boolean
   */
  public static function verifyUserExists($email)
  {
    $user = User::getUserByEmail($email);
    return $user instanceof User;
  }

  /**
   * Lida com a tentativa de Login do usuário
   * @param Request $request
   * @return string
   */
  public static function handleLogin($request)
  {
    $postVars = $request->getPostVars();
    $postVars = Form::cleanInput($postVars);
    $email = $postVars['email'];
    $password = $postVars['password'];


    if (!Form::validateInput([$email, $password])) {
      return self::getLoginPage($request, 'Sem entrada de dados');
    }

    if (!Form::validateEmail($email)) {
      return self::getLoginPage($request, 'Formato de e-mail incorreto');
    }

    if (!Form::validatePassword($password)) {
      return self::getLoginPage($request, 'Formato de senha incorreto');
    }

    $user = User::getUserByEmail($email);

    if (!$user instanceof User || !password_verify($password, $user->password)) {
      return self::getLoginPage($request, 'Usuário não encontrado');
    }

    if (!$user->is_admin) {
      return self::getLoginPage($request, 'Acesso permitido apenas para administradores');
    }

    Session::setUserSession($user);

    $request->getRouter()->redirect('/dashboard?status=welcome');
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
