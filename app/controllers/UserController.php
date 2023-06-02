<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\User;
use App\utils\Layout;
use App\utils\Modal;
use App\utils\Toast;

class UserController
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
   * Retorna os filtradores de usuários
   * @param array $params
   * @return string
   */
  private static function getFilters($params)
  {
    return View::render('partials/user-filters', [
      'search' => $params['search'] ?? '',
      'selected-user-type' => $params['user-type'] ?? '',
    ]);
  }

  /**
   * Filtra os usuários pelo nome
   * @param User $user
   * @param string $search
   * @return array
   */
  private static function filterUsers($grape, $search)
  {
    return stripos(strtolower(trim($grape->name)), strtolower(trim($search))) !== false;
  }

  /**
   * Retorna os cards de uva
   * @param array $params
   * @return string
   */
  private static function getUserCards($params)
  {
    $loggedUserId = Session::getUserSession()['id'];
    $users = User::getUsers($loggedUserId, $params);
    $cards = '';

    if (isset($params['search']) && $params['search'] !== '') {
      $users = array_filter(
        $users,
        fn ($user) => self::filterUsers($user, $params['search'])
      );
    }

    if (!count($users)) return '<p class="empty-message">Nenhum usuário encontrado.</p>';

    foreach ($users as $user) {
      $cards .= View::render('partials/user-card', [
        'id' => $user->id,
        'name' => $user->name,
        'user-type' => $user->is_admin ? 'administrador' : 'padrão',
        'color' => $user->is_admin ? 'var(--base-4)' : 'var(--primary)',
        'creator_name' => $user->creator_name,
      ]);
    }

    return $cards;
  }

  /**
   * Retorna o conteúdo (View) da página de uvas da Dashboard
   * @param Request $request
   * @return string
   */
  public static function getUserDashboardPage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    return View::render('pages/dashboard/user-dashboard', [
      'header' => Layout::getDashboardHeader('user'),
      'filters' => self::getFilters($params),
      'user-cards' => self::getUserCards($params),
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
        'type' => 'alter-password',
        'title' => 'Alterar senha',
      ]);
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
   * Retorna os usuários administradores que servirão como opções para o select input de usuário criador
   * @return string
   */
  private function getCreatorsOptions()
  {
    $admins = User::getAdminUsers();
    $options = '';

    foreach ($admins as $admin) {
      $options .= View::render('partials/creator-option', [
        'id' => $admin->id,
        'name' => $admin->name,
      ]);
    }

    return $options;
  }

  /**
   * Retorna os input de definição de senha de usuário
   * @return string
   */
  private function getPasswordInputs()
  {
    $inputs = View::render('partials/input', [
      'icon' => 'password',
      'label' => 'Senha',
      'field' => 'password',
    ]);

    $inputs .= View::render('partials/input', [
      'icon' => 'password',
      'label' => 'Confirmar Senha',
      'field' => 'password-confirm',
    ]);

    return $inputs;
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
  public static function getUserFormPage($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();

    $isEditForm = self::isEditForm($request);
    /**
     * @var User
     */
    $user = $isEditForm ? User::getUserById($id) : null;
    $modal = $isEditForm ? Modal::getModal(
      'trash',
      'Deletar uva ' . $user->name,
      'Tem certeza que deseja deletar essa uva?',
      '/dashboard/user/' . $user->id . '/delete',
      'delete'
    ) : '';

    return View::render('pages/dashboard/user-form', [
      'header' => Layout::getDashboardHeader('user'),
      'title' => $isEditForm ? 'Editar usuário ' . $user->name : 'Adicionar usuário',
      'modal' => $modal,
      'name' => $user ? $user->name : '',
      'email' => $user ? $user->email : '',
      'password-inputs' => !$isEditForm ? self::getPasswordInputs() : '',
      'creator_id' => $user ? $user->creator_id : '',
      'selected-user-type' => $user ? $user->is_admin : '',
      'creator-options' => self::getCreatorsOptions(),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
      'buttons' => self::getFormButtons($isEditForm, $user),
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
      $router->redirect("/dashboard/user/add/form?status=add-fail");
    }

    $user = new User;
    foreach ($postVars as $var => $value) {
      $user->{$var} = $value;
    }

    $user->add();

    $router->redirect("/dashboard/user?status=add-success");
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

    $grape = User::getGrapeById($id);

    if (!$grape instanceof User) {
      $router->redirect("/dashboard/user/$id/form?status=edit-fail");
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
      $router->redirect("/dashboard/user/$id/form?status=delete-fail");
    }

    $user = User::getUserById($id);

    if (!$user instanceof User) {
      $router->redirect("/dashboard/user/$id/edit");
    }

    $user->delete();

    $router->redirect("/dashboard/user?status=delete-success");
  }
}
