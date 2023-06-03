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
        return Toast::getError('Erro ao tentar adicionar uva!');
      case 'edit-success':
        return Toast::getSuccess('Uva editada com sucesso!');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar a uva');
      case 'delete-success':
        return Toast::getSuccess('Uva deletada com sucesso');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar a uva');
      default:
        return Toast::getError('Escreva uma mensagem no toast');
    }
  }

  /**
   * Retorna os filtradores de uvas
   * @param array $params
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
   * @param Grape $grape
   * @param string $search
   * @return array
   */
  private static function filterGrapes($grape, $search)
  {
    return stripos(strtolower(trim($grape->name)), strtolower(trim($search))) !== false;
  }

  /**
   * Retorna os cards de uva
   * @param array $params
   * @return string
   */
  private static function getGrapeCards($params)
  {
    $grapes = Grape::getGrapes();
    $cards = '';

    if (isset($params['search']) && $params['search'] !== '') {
      $grapes = array_filter(
        $grapes,
        fn ($grape) => self::filterGrapes($grape, $params['search'])
      );
    }

    if (!count($grapes)) return '<p class="empty-message">Nenhuma uva encontrada.</p>';

    foreach ($grapes as $grape) {
      $cards .= View::render('partials/grape-card', [
        'id' => $grape->id,
        'name' => $grape->name,
        'color_hex' => $grape->color_hex,
      ]);
    }

    return $cards;
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
      'header' => Layout::getDashboardHeader('grape'),
      'filters' => self::getFilters($params),
      'grape-cards' => self::getGrapeCards($params),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }

  /**
   * Retorna os botões paro formulário com base se é um formulário de edição ou não
   * @param boolean $isEditForm
   * @return string
   */
  private static function getFormButtons($isEditForm, $grape)
  {
    $buttons = '';

    if ($isEditForm) {
      $buttons .= View::render('partials/button', [
        'type' => 'edit',
        'title' => 'Editar',
        'value' => '/dashboard/grape/' . $grape->id . '/edit',
      ]);
      $buttons .= View::render('partials/button', [
        'type' => 'delete',
        'title' => 'Deletar',
        'value' => '/dashboard/grape/' . $grape->id . '/delete',
      ]);
    } else {
      $buttons .= View::render('partials/button', [
        'type' => 'add',
        'title' => 'Adicionar',
        'value' => '/dashboard/grape/add',
      ]);
    }

    return $buttons;
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
   * Retorna o conteúdo (View) da página de formulário de edição de uva
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getGrapeFormPage($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    $isEditForm = self::isEditForm($request);
    $grape = $isEditForm ? Grape::getGrapeById($id) : null;
    $modal = $isEditForm ? Modal::getModal(
      'trash',
      'Deletar uva ' . $grape->name,
      'Tem certeza que deseja deletar essa uva?',
      '/dashboard/grape/' . $grape->id . '/delete',
      'delete'
    ) : '';

    return View::render('pages/dashboard/grape-form', [
      'header' => Layout::getDashboardHeader('grape'),
      'title' => $isEditForm ? 'Editar uva ' . $grape->name : 'Adicionar uva',
      'modal' => $modal,
      'name' => $grape ? $grape->name : '',
      'color_hex' => $grape ? $grape->color_hex : '',
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
      'buttons' => self::getFormButtons($isEditForm, $grape),
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

    foreach ($data as $value) {
      if ($value == '') {
        return false;
      }
    }

    return !!$data;
  }

  /**
   * Adiciona uma uva
   * @param Request $request
   * @param integer $id
   */
  public static function addGrape($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = $request->getPostVars();

    if (!self::isValidateInput($postVars)) {
      $router->redirect("/dashboard/grape/add/form?status=add-fail");
    }

    $grape = new Grape;
    foreach ($postVars as $var => $value) {
      $grape->{$var} = $value;
    }

    $grape->add();

    $router->redirect("/dashboard/grape?status=add-success");
  }

  /**
   * Atualiza uma uva com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function editgrape($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = $request->getPostVars();

    if (!is_numeric($id) || !self::isValidateInput($postVars)) {
      $router->redirect("/dashboard/grape/$id/form?status=edit-fail");
    }

    $grape = Grape::getGrapeById($id);

    if (!$grape instanceof Grape) {
      $router->redirect("/dashboard/grape/$id/form?status=edit-fail");
    }

    foreach ($postVars as $var => $value) {
      $grape->{$var} = $value ?? $grape->{$var};
    }

    $grape->update();

    $router->redirect("/dashboard/grape/$id/form?status=edit-success");
  }

  /**
   * Deleta uma uva com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function deleteGrape($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();

    if (!is_numeric($id)) {
      $router->redirect("/dashboard/grape/$id/form?status=delete-fail");
    }

    $grape = Grape::getGrapeById($id);

    if (!$grape instanceof Grape) {
      $router->redirect("/dashboard/grape/$id/edit?status=delete-fail");
    }

    $grape->delete();

    $router->redirect("/dashboard/grape?status=delete-success");
  }
}
