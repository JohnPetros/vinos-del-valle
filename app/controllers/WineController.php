<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use \App\utils\Layout;
use \App\models\Wine;

class WineController
{

  /**
   * Retorna os cards de vinho
   * @param array $params
   * @return string
   */
  private static function getWineCards($params)
  {
    $wines = Wine::getWines($params);
    $cards = '';

    foreach ($wines as $wine) {
      $cards .= View::render('partials/wine-card', [
        'id' => $wine->id,
        'name' => $wine->name,
        'country_code' => $wine->country_code,
        'bottling_date' => $wine->bottling_date,
        'grape' => $wine->grape,
      ]);
    }

    return $cards;
  }

  /**
   * Retorna o conteúdo (View) da página de vinhos da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getDashboardWinePage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $filters =  View::render('partials/wine-filters');
    $params = $request->getQueryParams();

    return View::render('pages/dashboard/wine-dashboard', [
      'header' => Layout::getDashboardHeader(),
      'filters' => $filters,
      'wine-cards' => self::getWineCards($params),
    ]);
  }
}
