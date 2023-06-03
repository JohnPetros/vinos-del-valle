<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\Grape;
use App\models\Region;
use App\models\User;
use App\models\Wine;
use App\utils\Layout;

class DashboardController
{

  /**
   * Retorna os paineis que exibem quantos registros há em cada tabela
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

    return View::render('pages/dashboard/dashboard', [
      'header' => Layout::getDashboardHeader('dashboard'),
      'panels' => self::getPanels(),
    ]);
  }
}
