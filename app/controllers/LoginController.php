<?php

namespace App\controllers;

use \App\core\View;
use App\utils\Toast;

class LoginController
{
  /**
   * Método responsável por retornar o conteúdo (View) da página de login
   * @return string
   */
  public static function getLoginPage()
  {
    return View::render('pages/login');
  }

  /**
   * Método responsável por definir o Login do usuário
   * @param Request $request
   * @return string
   */
  public static function handleLogin($request)
  {
    // POST VARS
    $postVars = $request->getPostVars();
    $email = $postVars['email'] ?? '';
    $password = $postVars['password'] ?? '';

    echo '<pre>';
    print_r($email);
    echo '</pre>';
    exit;


    // // BUSCA O USUÁRIO PELO E-MAIL
    // $user = User::getUserByEmail($email);
    // if (!$user instanceof User) {
    //   return self::getLogin($request, 'E-mail ou senha inválidos');
    // }

    // // VERIFICA A SENHA DO USUÁRIO
    // if (!password_verify($password, $user->password)) {
    //   return self::getLogin($request, 'E-mail ou senha inválidos');
    // }

    // // CRIA A SESSÃO DE LOGIN
    // SessionAdminLogin::login($user);

    // REDIRECIONA O USUÁRIO PARA /ADMIN
    $request->getRouter()->redirect('/admin');
  }
}
