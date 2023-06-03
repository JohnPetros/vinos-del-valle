<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\utils\Layout;

class DashboardController
{
  /**
   * Retorna o conteúdo (View) da página de Dashboard
   * @param Request $request
   * @return string
   */
  public static function getDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    return View::render('pages/dashboard/dashboard', [
      'header' => Layout::getDashboardHeader('dashboard'),
    ]);
  }
}
