<?php

namespace App\controllers;

use \App\core\View;

class LoginController
{
  /**
   * Método responsável por retornar o conteúdo (View) da página de login
   * @return string
   */
  public static function getLoginPage()
  {
    return View::render('pages/login', [
      'name' => 'Márcio Suga'
    ]);
  }
}
