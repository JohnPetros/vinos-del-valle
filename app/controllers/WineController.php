<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use \App\utils\Layout;
use \App\models\Wine;
use \App\models\Region;
use App\models\Grape;
use App\utils\Country;
use App\utils\Form;
use App\utils\Modal;
use App\utils\Toast;
use App\utils\Year;
use DateTime;

class WineController
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
        return Toast::getSuccess('Vinho adicionado');
      case 'add-fail':
        return Toast::getError('Erro ao tentar adicionar vinho');
      case 'edit-success':
        return Toast::getSuccess('Vinho editado');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar o vinho!');
      case 'delete-success':
        return Toast::getSuccess('Vinho deletado');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar o vinho');
      case 'grape-fail':
        return Toast::getError('Vinhos com o mesmo nome não podem ter uvas diferentes');
      default:
        return Toast::getError('Escreva uma mensagem no toast...');
    }
  }

  /**
   * Filtra os vinhos pelo nome
   * @param array $wines
   * @param string $search
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

    if (!count($wines)) return '<p class="empty-message">Nenhum vinho encontrado.</p>';

    foreach ($wines as $wine) {
      $cards .= View::render('partials/wine-card', [
        'id' => $wine->id,
        'name' => $wine->name,
        'country_name' => Country::getCountryByCode($wine->country_code)->name,
        'harvest_date' => $wine->harvest_date,
        'grape_name' => $wine->grape_name,
        'color_hex' => $wine->color_hex,
      ]);
    }

    return $cards;
  }

  /**
   * Retorna os anos, que servirão como opções para o select de anos
   * @return string
   */
  private static function getYearOptions()
  {
    $years = Year::getLastYears();
    $options = '';

    foreach ($years as $year) {
      $options .= View::render('partials/year-option', [
        'year' => $year,
      ]);
    }

    return $options;
  }

  /**
   * Retorna as regiões, que servirão como opções para o select de regiões
   * @param boolean $canIncludeAll
   * @return string
   */
  private static function getRegionOptions($canIncludeAll = false)
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
  private static function getGrapeOptions()
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
  private static function getGrapeCategories()
  {
    $grapes = Grape::getGrapes();
    $categories = '';

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
   * @param array $params
   * @return string
   */
  private static function getFilters($params)
  {
    return View::render('partials/wine-filters', [
      'selected-year' => $params['year'] ?? 'all-years',
      'selected-region-id' => $params['region'] ?? 'all-regions',
      'search' => $params['search'] ?? '',
      'year-options' => self::getYearOptions(),
      'region-options' => self::getRegionOptions(true),
      'grape-categories' => self::getGrapeCategories(),
    ]);
  }

  /**
   * Retorna o conteúdo (View) da página de vinhos da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getWineDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();


    return View::render('pages/dashboard/wine-dashboard', [
      'header' => Layout::getDashboardHeader('wine'),
      'filters' => self::getFilters($params),
      'wine-cards' => self::getWineCards($params),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }

  /**
   * Retorna o input de data de cadastro
   * @param string $data
   * @return string
   */
  private static function getInputRegistrationDate($date)
  {
    $formatedDate = (new DateTime($date))->format('Y-m-d');

    return View::render('partials/input-registration-date', [
      'label' => 'Data de cadastro',
      'date' => $formatedDate,
    ]);
  }

  /**
   * Verifica se o usuário está requisitando um formulário de edição
   * @param Request $request
   * @return boolean
   */
  private static function isEditForm($request)
  {
    $uriPartials =  explode('/', $request->getUri());
    return is_numeric($uriPartials[3]);
  }

  /**
   * Retorna os botões paro formulário com base se é um formulário de edição ou não
   * @param boolean $isEditForm
   * @param Wine $wine
   * @return string
   */
  private static function getFormButtons($isEditForm, $wine)
  {
    $buttons = '';

    if ($isEditForm) {
      $buttons .= View::render('partials/button', [
        'type' => 'edit',
        'title' => 'Editar',
        'value' => '/dashboard/wine/' . $wine->id . '/edit',
      ]);
      $buttons .= View::render('partials/button', [
        'type' => 'delete',
        'title' => 'Deletar',
        'value' => '/dashboard/wine/' . $wine->id . '/delete',
      ]);
    } else {
      $buttons .= View::render('partials/button', [
        'type' => 'add',
        'title' => 'Adicionar',
        'value' => '/dashboard/wine/add',
      ]);
    }

    return $buttons;
  }

  /**
   * Retorna o conteúdo (View) da página de formulário de edição de vinho
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getWineFormPage($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    $isEditForm = self::isEditForm($request);
    $wine = $isEditForm ? Wine::getWineById($id) : null;
    $modal = $isEditForm ? Modal::getModal(
      'trash',
      'Deletar vinho ' . $wine->name,
      'Tem certeza que deseja deletar esse vinho?',
      '/dashboard/wine/' . $wine->id . '/delete',
      'delete'
    ) : '';

    return View::render('pages/dashboard/wine-form', [
      'header' => Layout::getDashboardHeader('wine'),
      'title' => $isEditForm ? 'Editar vinho ' . $wine->name : 'Adicionar vinho',
      'modal' => $modal,
      'id' => $wine ? $wine->id : '',
      'name' => $wine ? $wine->name : '',
      'winery' => $wine ? $wine->winery : '',
      'grape' => $wine ? $wine->grape_name : '',
      'region' => $wine ? $wine->region_name : '',
      'region-options' => self::getRegionOptions(),
      'grape-options' => self::getGrapeOptions(),
      'year-options' => self::getYearOptions(),
      'selected-region-id' => $wine ? $wine->region_id : 'all-regions',
      'selected-grape-id' => $wine ? $wine->grape_id : 'all-grapes',
      'harvest_date' => $wine ? $wine->harvest_date : '',
      'bottling_date' => $wine ? $wine->bottling_date : '',
      'registration_date' => $isEditForm
        ? self::getInputRegistrationDate($wine->registration_date)
        : '',
      'buttons' => self::getFormButtons($isEditForm, $wine),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }

  /**
   * Adiciona um vinho
   * @param Request $request
   */
  public static function addWine($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());

    if (
      !Form::validateInput($postVars) ||
      !is_numeric($postVars['grape_id']) ||
      !is_numeric($postVars['region_id'])
    ) {
      $router->redirect("/dashboard/wine/add/form?status=add-fail");
    }

    $wine = Wine::getWineByName($postVars['name']);
    if ($wine instanceof Wine && $wine->grape_id != $postVars['grape_id']) {
      $router->redirect("/dashboard/wine/add/form?status=grape-fail");
    }

    $wine = new Wine;
    foreach ($postVars as $var => $value) {
      $wine->{$var} = $value;
    }

    $wine->add();

    $router->redirect("/dashboard/wine?status=add-success");
  }

  /**
   * Atualiza um vinho com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function editWine($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());

    if (
      !Form::validateInput($postVars) ||
      !is_numeric($id) ||
      !is_numeric($postVars['region_id']) ||
      !is_numeric($postVars['grape_id'])
    ) {
      $router->redirect("/dashboard/wine/$id/form?status=edit-fail");
    }

    $wine = Wine::getWineByName($postVars['name']);
    if ($wine instanceof Wine && $wine->grape_id != $postVars['grape_id']) {
      $router->redirect("/dashboard/wine/$id/form?status=grape-fail");
    }

    $wine = Wine::getWineById($id);
    if (!$wine instanceof Wine) {
      $router->redirect("/dashboard/wine/$id/form?status=edit-fail");
    }

    foreach ($postVars as $var => $value) {
      $wine->{$var} = $value ?? $wine->{$var};
    }

    $wine->update();

    $router->redirect("/dashboard/wine/$id/form?status=edit-success");
  }

  /**
   * Deleta um vinho com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function deleteWine($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();

    if (!is_numeric($id)) {
      $router->redirect("/dashboard/wine/$id/form?status=delete-fail");
    }

    $wine = Wine::getWineById($id);

    if (!$wine instanceof Wine) {
      $router->redirect("/dashboard/wine/$id/edit?status=delete-fail");
    }

    $wine->delete();

    $router->redirect("/dashboard/wine?status=delete-success");
  }
}
