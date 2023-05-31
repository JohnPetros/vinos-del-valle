<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\Grape;
use App\utils\Layout;
use App\utils\Modal;
use App\utils\Toast;

class GrapeController
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
        return Toast::getSuccess('Uva adicionada com sucesso!');
      case 'add-fail':
        return Toast::getSuccess('Erro ao tentar adicionar com sucesso!');
      case 'edit-success':
        return Toast::getSuccess('Uva editado com sucesso!');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar a região');
      case 'delete-success':
        return Toast::getSuccess('Uva deletada com sucesso');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar a região');
      default:
        return Toast::getError('Escreva uma mensagem no toast');
    }
  }

  /**
   * Retorna os filtradores de uvas
   * @return string
   */
  private static function getFilters($params)
  {
    return View::render('partials/grape-filters', [
      'search' => $params['search'] ?? '',
    ]);
  }

  /**
   * Filtra as uvas pelo nome
   * @param array $wines
   * @return array
   */
  private static function filterRegions($region, $search)
  {
    return stripos(strtolower(trim($region->name)), strtolower(trim($search))) !== false;
  }


  /**
   * Retorna o conteúdo (View) da página de uvas da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getGrapeDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    return View::render('pages/dashboard/grape-dashboard', [
      'header' => Layout::getDashboardHeader('region'),
      'filters' => self::getFilters($params),
      // 'grape-cards' => self::getRegionCards($params),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }
}
