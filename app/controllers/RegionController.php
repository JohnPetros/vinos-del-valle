<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
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
    return View::render('partials/wine-filters', [
      'search' => $params['search'] ?? '',
    ]);
  }

  /**
   * Retorna o conteúdo (View) da página de regiões da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getRegionDashboardPage($request)
  {
    $params = $request->getQueryParams();

    return View::render('pages/dashboard/region-dashboard', [
      'header' => Layout::getDashboardHeader(),
      'filters' => self::getFilters($params),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }
}
