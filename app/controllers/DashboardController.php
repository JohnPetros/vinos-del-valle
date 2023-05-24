<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;

class DashboardController
{

  /**
   * Método responsável por retornar o conteúdo (View) da página de login
   * @param string $errorMessage
   * @return string
   */
  public static function getDashboardPage($request)
  {
    
    return View::render('pages/dashboard');
  }
}
