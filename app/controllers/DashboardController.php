<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;

class DashboardController
{

  /**
   * Método responsável por retornar o conteúdo (View) da página de Dashboard
   * @param string $errorMessage
   * @return string
   */
  public static function getDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);
    return View::render('pages/dashboard');
  }
}
