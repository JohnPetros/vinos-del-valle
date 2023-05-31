<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\Region;
use App\utils\Layout;
use App\utils\Toast;

class RegionController
{

  /**
   * Retorna o toast junto com a mensagem criada com base no status
   * @param string $status
   * @return string
   */
  private static function getToast($status)
  {
    switch ($status) {
      case 'add-success':
        return Toast::getSuccess('Região adicionada com sucesso!');
      case 'add-fail':
        return Toast::getSuccess('Erro ao tentar adicionar com sucesso!');
      case 'edit-success':
        return Toast::getSuccess('Região editado com sucesso!');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar a região');
      case 'delete-success':
        return Toast::getSuccess('Região deletada com sucesso');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar a região');
      default:
        return Toast::getError('Escreva uma mensagem no toast');
    }
  }

  /**
   * Retorna os filtradores de regiões
   * @return string
   */
  private static function getFilters($params)
  {
    return View::render('partials/region-filters', [
      'search' => $params['search'] ?? '',
    ]);
  }

  /**
   * Filtra as regiões pelo nome
   * @param array $wines
   * @return array
   */
  private static function filterRegions($region, $search)
  {
    return stripos(strtolower(trim($region->name)), strtolower(trim($search))) !== false;
  }

  /**
   * Retorna os cards de região
   * @param array $params
   * @return string
   */
  private static function getRegionCards($params)
  {
    $regions = Region::getRegions();
    $cards = '';

    if (isset($params['search']) && $params['search'] !== '') {
      $regions = array_filter(
        $regions,
        fn ($wine) => self::filterRegions($wine, $params['search'])
      );
    }

    if (!count($regions)) return '<p class="empty-message">Nenhuma região cadastrada.</p>';

    foreach ($regions as $region) {
      $cards .= View::render('partials/region-card', [
        'id' => $region->id,
        'name' => $region->name,
        'city' => $region->city,
        'state' => $region->state,
        'country_code' => $region->country_code,
      ]);
    }

    return $cards;
  }

  /**
   * Retorna o conteúdo (View) da página de regiões da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getRegionDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    return View::render('pages/dashboard/region-dashboard', [
      'header' => Layout::getDashboardHeader(),
      'filters' => self::getFilters($params),
      'region-cards' => self::getRegionCards($params),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }
}
