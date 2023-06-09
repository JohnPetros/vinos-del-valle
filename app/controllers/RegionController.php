<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\Region;
use App\utils\Country;
use App\utils\Form;
use App\utils\Layout;
use App\utils\Modal;
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
        return Toast::getSuccess('Região adicionada');
      case 'add-fail':
        return Toast::getSuccess('Erro ao tentar adicionar região');
      case 'edit-success':
        return Toast::getSuccess('Região editada');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar a região');
      case 'empty-input':
        return Toast::getError('Entrada de dados não fornecida');
      case 'delete-success':
        return Toast::getSuccess('Região deletada com sucesso');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar a região');
      default:
        return Toast::getError('Escreva uma mensagem no toast');
    }
  }

  /**
   * Retorna os países, que servirão como opções para o select de país
   * @return string
   */
  public static function getCountryOptions()
  {
    $countries = Country::getCountries();
    $options = '';

    foreach ($countries as $country) {
      $options .= View::render('partials/country-option', [
        'code' => $country->code,
        'name' => $country->name,
      ]);
    }
    return $options;
  }

  /**
   * Retorna os países, que servirão filtrar as regiões por país
   * @return string
   */
  public static function getCountryCategories()
  {
    $countries = Country::getCountries();
    $categories = '';

    foreach ($countries as $country) {
      $categories .= View::render('partials/category', [
        'id' => $country->code,
        'name' => $country->name,
        'color_hex' => $country->color_hex,
      ]);
    }
    return $categories;
  }

  /**
   * Retorna os filtradores de regiões
   * @return string
   */
  private static function getFilters($params)
  {
    return View::render('partials/region-filters', [
      'search' => $params['search'] ?? '',
      'country-categories' => self::getCountryCategories(),
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
    $category = isset($params['category']) && $params['category'] !== 'all-categories'
      ? $params['category']
      : null;

    $regions = Region::getRegions($category);
    $cards = '';

    if (isset($params['search']) && $params['search'] !== '') {
      $regions = array_filter(
        $regions,
        fn ($region) => self::filterRegions($region, $params['search'])
      );
    }

    if (!count($regions)) return '<p class="empty-message">Nenhuma região encontrada.</p>';

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
      'header' => Layout::getDashboardHeader('region'),
      'filters' => self::getFilters($params),
      'region-cards' => self::getRegionCards($params),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }

  /**
   * Retorna os botões paro formulário com base se é um formulário de edição ou não
   * @param boolean $isEditForm
   * @return string
   */
  private static function getFormButtons($isEditForm, $region)
  {
    $buttons = '';

    if ($isEditForm) {
      $buttons .= View::render('partials/button', [
        'type' => 'edit',
        'title' => 'Editar',
        'value' => URL . '/dashboard/region/' . $region->id . '/edit',
      ]);
      $buttons .= View::render('partials/button', [
        'type' => 'delete',
        'title' => 'Deletar',
        'value' => URL . '/dashboard/region/' . $region->id . '/delete',
      ]);
    } else {
      $buttons .= View::render('partials/button', [
        'type' => 'add',
        'title' => 'Adicionar',
        'value' => URL . '/dashboard/region/add',
      ]);
    }

    return $buttons;
  }

  /**
   * Retorna o conteúdo (View) da página de formulário de edição de região
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getRegionFormPage($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    $isEditForm = is_numeric($id);
    $region = $isEditForm ? Region::getRegionById($id) : null;
    $modal = $isEditForm ? Modal::getModal(
      'trash',
      'Deletar região ' . $region->name,
      'Tem certeza que deseja deletar essa região?',
      URL . '/dashboard/region/' . $region->id . '/delete',
      'delete'
    ) : '';

    return View::render('pages/dashboard/region-form', [
      'header' => Layout::getDashboardHeader('region'),
      'title' => $isEditForm ? 'Editar região ' . $region->name : 'Adicionar região',
      'modal' => $modal,
      'name' => $region ? $region->name : '',
      'city' => $region ? $region->city : '',
      'state' => $region ? $region->state : '',
      'country-options' => self::getCountryOptions(),
      'selected-country-code' => $region ? $region->country_code : '',
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
      'buttons' => self::getFormButtons($isEditForm, $region),
    ]);
  }

  /**
   * Adiciona uma região
   * @param Request $request
   * @param integer $id
   */
  public static function addRegion($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());

    if (!Form::validateInput($postVars)) {
      $router->redirect("/dashboard/region/add/form?status=empty-input");
    }

    $region = new Region;
    foreach ($postVars as $var => $value) {
      $region->{$var} = $value;
    }

    $region->add();

    $router->redirect("/dashboard/region?status=add-success");
  }

  /**
   * Atualiza uma região com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function editRegion($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());

    if (!Form::validateInput($postVars)) {
      $router->redirect("/dashboard/region/add/form?status=empty-input");
    }

    if (!is_numeric($id)) {
      $router->redirect("/dashboard/region/$id/form?status=edit-fail");
    }

    $region = Region::getRegionById($id);

    if (!$region instanceof Region) {
      $router->redirect("/dashboard/region/$id/form?status=edit-fail");
    }

    foreach ($postVars as $var => $value) {
      $region->{$var} = $value ?? $region->{$var};
    }

    $region->update();

    $router->redirect("/dashboard/region/$id/form?status=edit-success");
  }

  /**
   * Deleta uma região com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function deleteRegion($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();

    if (!is_numeric($id)) {
      $router->redirect("/dashboard/region/$id/form?status=delete-fail");
    }

    $region = Region::getRegionById($id);

    if (!$region instanceof Region) {
      $router->redirect("/dashboard/region/$id/edit?status=delete-fail");
    }

    $region->delete();

    $router->redirect("/dashboard/region?status=delete-success");
  }
}
