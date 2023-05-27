<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use \App\utils\Layout;

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

    $filters =  View::render('partials/wine-filters');

    return View::render('pages/dashboard/wine-dashboard', [
      'header' => Layout::getDashboardHeader(),
      'filters' => $filters,
    ]);
  }
}
