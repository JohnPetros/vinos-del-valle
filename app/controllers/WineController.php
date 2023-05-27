<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use \App\utils\Layout;
use \App\models\Wine;
use \App\models\Region;

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

    if (!count($wines)) return '<p class="empty-message">Nenhum vinho cadastrado.</p>';

    foreach ($wines as $wine) {
      $cards .= View::render('partials/wine-card', [
        'id' => $wine->id,
        'name' => $wine->name,
        'country_code' => $wine->country_code,
        'harvest_date' => $wine->harvest_date,
        'grape' => $wine->grape,
        'color_hex' => $wine->color_hex,
      ]);
    }

    return $cards;
  }

  /**
   * Retorna as regiões que servirão como opções para o select
   * @return string
   */
  public static function getRegionOptions()
  {
    $regions = Region::getRegions();
    $options = View::render('partials/region-option', [
      'id' => 'all',
      'name' => 'Todas as regiões',
      'country_code' => 'AQ',
    ]);;

    foreach ($regions as $region) {
      $options .= View::render('partials/region-option', [
        'id' => $region->id,
        'name' => $region->name,
        'country_code' => $region->country_code,
      ]);
    }

    return $options;
  }

  /**
   * Retorna os filtradores de vinhos
   * @return string
   */
  public static function getFilters()
  {
    return View::render('partials/wine-filters', [
      'region-options' => self::getRegionOptions(),
    ]);
  }

  /**
   * Retorna o conteúdo (View) da página de vinhos da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getDashboardWinePage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    return View::render('pages/dashboard/wine-dashboard', [
      'header' => Layout::getDashboardHeader(),
      'filters' => self::getFilters(),
      'wine-cards' => self::getWineCards($params),
    ]);
  }
}
