<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;

class WineController
{

  /**
   * Retorna o conteúdo (View) da página de vinhos da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getDashboardWinePage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $header =  View::render('partials/header');
    $filters =  View::render('partials/filters');

    return View::render('pages/dashboard', [
      'header' => $header,
      'filters' => $filters,
    ]);
  }
}
