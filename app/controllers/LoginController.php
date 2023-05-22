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

    $toast = Toast::getError("Login feito com sucesso!");

    return View::render('pages/login', [
      'toast' => $toast
    ]);
  }
}
