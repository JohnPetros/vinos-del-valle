<?php

namespace App\controllers;

use \App\core\View;
use \App\core\Session;
use \App\utils\Layout;
use \App\models\Wine;
use \App\models\Region;
use App\models\Grape;
use App\utils\Modal;
use App\utils\Toast;
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
      case 'welcome':
        return Toast::getSuccess('Seja bem-vindo ' . (Session::getUserSession()['name']));
      case 'add-success':
        return Toast::getSuccess('Vinho adicionado com sucesso!');
      case 'add-fail':
        return Toast::getSuccess('Erro ao tentar adicionar um vinho');
      case 'edit-success':
        return Toast::getSuccess('Vinho editado com sucesso');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar o vinho');
      case 'delete-success':
        return Toast::getSuccess('Vinho deletado com sucesso');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar o vinho');
      default:
        return Toast::getError('Escreva uma mensagem no toast');
    }
  }

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
  private static function getFilters($params)
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
      'grape' => $wine ? $wine->grape : '',
      'region' => $wine ? $wine->region : '',
      'region-options' => self::getRegionOptions(),
      'grape-options' => self::getGrapeOptions(),
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
   * Verifica se a entrada de dados do usuário é válido
   * @param array $data 
   * @return boolean
   */
  private static function isValidateInput($data)
  {
    $data = array_map('trim', $data);

    $data = filter_input_array(INPUT_POST, $data);

    foreach ($data as $key => $value) {
      if ($value == '') {
        return false;
      }

      if (($key == 'region_id' || $key == 'grape_id') && !is_numeric($value)) {
        return false;
      }
    }

    return $data;
  }

  /**
   * Adiciona um vinho
   * @param Request $request
   * @param integer $id
   */
  public static function addWine($request)
  {
    $router = $request->getRouter();
    $postVars = $request->getPostVars();

    if (!self::isValidateInput($postVars)) {
      $router->redirect("/dashboard/wine/add/form?status=add-fail");
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
    $router = $request->getRouter();
    $postVars = $request->getPostVars();

    if (!is_numeric($id) || !self::isValidateInput($postVars)) {
      $router->redirect("/dashboard/wine/$id/form?status=edit-fail");
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
    $router = $request->getRouter();

    if (!is_numeric($id)) {
      $router->redirect("/dashboard/wine/$id/form?status=delete-fail");
    }

    $wine = Wine::getWineById($id);

    if (!$wine instanceof Wine) {
      $router->redirect("/dashboard/wine/$id/edit");
    }

    $wine->delete();

    $router->redirect("/dashboard/wine?status=deleted");
  }
}
