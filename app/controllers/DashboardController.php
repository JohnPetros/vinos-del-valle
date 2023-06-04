<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\Grape;
use App\models\Region;
use App\models\User;
use App\models\Wine;
use App\utils\Chart;
use App\utils\Layout;
use App\utils\Toast;

class DashboardController
{

  /**
   * Retorna o toast junto com a mensagem criada com base no status
   * @param string $status
   * @return string
   */
  private static function getToast($status)
  {
    switch ($status) {
      case 'welcome':
        return Toast::getSuccess('Seja bem-vindo ' . (Session::getUserSession()['name']));
      default:
        return Toast::getError('Escreva uma mensagem no toast...');
    }
  }

  /**
   * Retorna os gráficos de dashboard
   * @return string
   */
  private static function getCharts()
  {
    $chartsData = [
      Chart::getWinesByGrapeChartData(),
      Chart::getWinesByRegionChartData(),
      Chart::getWinesByCountryChartData(),
      Chart::getWinesByHarvestYearChartData(),
      Chart::getWinesAmountChartData(),
    ];
    $charts = '';

    foreach ($chartsData as $chartData) {
      $charts .= View::render('partials/chart', [
        'id' => $chartData['id'],
        'title' => $chartData['title'],
        'color' => $chartData['color'],
        'data' => $chartData['data'],
        'categories' => $chartData['categories'],
      ]);
    }

    return $charts;
  }

  /**
   * Retorna os paineis que exibem quantos registros há em cada entidade do banco de dados
   * @return string
   */
  private static function getPanels()
  {
    $entities = [
      [
        'name' => 'wine',
        'title' => 'Vinhos',
        'amount' => Wine::getWinesAmount(),
        'icon' => 'wine',
      ],
      [
        'name' => 'grape',
        'title' => 'Uvas',
        'amount' => Grape::getGrapesAmount(),
        'icon' => 'graph',
      ],
      [
        'name' => 'region',
        'title' => 'Regiões',
        'amount' => Region::getRegionsAmount(),
        'icon' => 'house-line',
      ],
      [
        'name' => 'user',
        'title' => 'Usuários',
        'amount' => User::getUsersAmount(),
        'icon' => 'user',
      ],
    ];

    $panels = '';
    foreach ($entities as $entity) {
      $panels .= View::render('partials/panel', [
        'name' => $entity['name'],
        'title' => $entity['title'],
        'amount' => $entity['amount'],
        'icon' => $entity['icon'],
      ]);
    }

    return $panels;
  }

  /**
   * Retorna o conteúdo (View) da página de Dashboard
   * @param Request $request
   * @return string
   */
  public static function getDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    return View::render('pages/dashboard/dashboard', [
      'header' => Layout::getDashboardHeader('dashboard'),
      'panels' => self::getPanels(),
      'charts' => self::getCharts(),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }
}
