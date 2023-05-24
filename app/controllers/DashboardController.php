<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;

class DashboardController
{

  /**
   * Retorna o conteúdo (View) da página de Dashboard
   * @param string $errorMessage
   * @return string
   */
  public static function getDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $header =  View::render('partials/header');
    $search =  View::render('partials/search');

    return View::render('pages/dashboard', [
      'header' => $header,
      'search' => $search,
    ]);
  }
}
