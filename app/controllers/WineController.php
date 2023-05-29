<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use \App\utils\Layout;
use \App\models\Wine;
use \App\models\Region;
use App\models\Grape;

class WineController
{

  /**
   * Filtra os vinhos pelo nome
   * @param array $wines
   * @return array
   */
  private static function filterWines($wine, $search)
  {
    return stripos(strtolower(trim($wine->name)), strtolower(trim($search))) !== false;
  }

  /**
   * Retorna os cards de vinho
   * @param array $params
   * @return string
   */
  private static function getWineCards($params)
  {
    $wines = Wine::getWines($params);
    $cards = '';

    if (isset($params['search']) && $params['search'] !== '') {
      $wines = array_filter(
        $wines,
        fn ($wine) => self::filterWines($wine, $params['search'])
      );
    }

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
   * Retorna as regiões, que servirão como opções para o select de regiões
   * @return string
   */
  public static function getRegionOptions($canIncludeAll = false)
  {
    $regions = Region::getRegions();
    $options = $canIncludeAll ? View::render('partials/region-option', [
      'id' => 'all-regions',
      'name' => 'Todas as regiões',
      'country_code' => 'AQ',
    ]) : '';

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
   * Retorna as uvas, que servirão opções para o select de uvas
   * @return string
   */
  public static function getGrapeOptions()
  {
    $grapes = Grape::getGrapes();
    $options = '';

    foreach ($grapes as $grape) {
      $options .= View::render('partials/grape-option', [
        'id' => $grape->id,
        'name' => $grape->name,
        'color_hex' => $grape->color_hex,
      ]);
    }

    return $options;
  }

  /**
   * Retorna as uvas, que servirão para filtrar vinhos por categoria
   * @return string
   */
  public static function getGrapeCategories()
  {
    $grapes = Grape::getGrapes();
    $categories = "";

    foreach ($grapes as $grape) {
      $categories .= View::render('partials/category', [
        'id' => $grape->id,
        'name' => $grape->name,
        'color_hex' => $grape->color_hex,
      ]);
    }

    return $categories;
  }

  /**
   * Retorna os filtradores de vinhos
   * @return string
   */
  public static function getFilters($params)
  {
    return View::render('partials/wine-filters', [
      'selected-year' => $params['year'] ?? 'all-years',
      'selected-region-id' => $params['region'] ?? 'all-regions',
      'search' => $params['search'] ?? '',
      'region-options' => self::getRegionOptions(true),
      'grape-categories' => self::getGrapeCategories(),
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
      'filters' => self::getFilters($params),
      'wine-cards' => self::getWineCards($params),
    ]);
  }

  /**
   * Retorna o conteúdo (View) da página de formulário de edição de vinho
   * @param Request $request
   * @param Request $id
   * @return string
   */
  public static function getWineFormPage($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $isEditForm = strpos($request->getUri(), '/edit') !== false;

    $wine = $isEditForm ? Wine::getWineById($id) : null;

    return View::render('pages/dashboard/wine-form', [
      'header' => Layout::getDashboardHeader(),
      'name' => $wine->name ?? '',
      'winery' => $wine->winery ?? '',
      'grape' => $wine->grape ?? '',
      'region' => $wine->region ?? '',
      'region-options' => self::getRegionOptions(),
      'grape-options' => self::getGrapeOptions(),
      'selected-region-id' => $wine->region_id ?? 'all-regions',
      'selected-grape-id' => $wine->region_id ?? 'all-grapes',
      'registration_date' => $wine->registration_date ?? '',
      'harvest_date' => $wine->harvest_date ?? '',
      'bottling_date' => $wine->bottling_date ?? '',
    ]);
  }
}
