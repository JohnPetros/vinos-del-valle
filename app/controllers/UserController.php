<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\models\User;
use App\utils\File;
use App\utils\Form;
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
        return Toast::getSuccess('Usuário adicionado');
      case 'add-fail':
        return Toast::getError('Erro ao tentar adicionar usuário');
      case 'edit-success':
        return Toast::getSuccess('Usuário editado');
      case 'edit-fail':
        return Toast::getError('Erro ao tentar editar usuário');
      case 'empty-input':
        return Toast::getError('Entrada de dados não fornecida');
      case 'email-fail':
        return Toast::getError('Formato de E-mail inválido');
      case 'password-fail':
        return Toast::getError('Formato de senha inválido');
      case 'password_confirm-fail':
        return Toast::getError('Senhas não conferem');
      case 'email-equal':
        return Toast::getError('E-mail já em uso');
      case 'avatar-fail':
        return Toast::getError('Imagem de avatar inválida');
      case 'delete-success':
        return Toast::getSuccess('Usuário deletado');
      case 'delete-fail':
        return Toast::getError('Erro ao tentar deletar o Usuário');
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
   * Verifica se um avatar se existe na aplicação
   * @param string $avatar
   * @return boolean
   */
  public static function verifyAvatarExists($avatar)
  {
    return file_exists(__DIR__ . '/../../public/uploads/avatars/' . $avatar);
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
        'avatar' => self::verifyAvatarExists($user->avatar) ? $user->avatar : 'default.png',
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
  private static function getFormButtons($isEditForm, $user)
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
        'value' => '/dashboard/user/' . $user->id . '/edit',
      ]);
      $buttons .= View::render('partials/button', [
        'type' => 'delete',
        'title' => 'Deletar',
        'value' => '/dashboard/user/' . $user->id . '/delete',
      ]);
    } else {
      $buttons .= View::render('partials/button', [
        'type' => 'add',
        'title' => 'Adicionar',
        'value' => '/dashboard/user/add',
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
   * Retorna o select conctendo os usuários adinistradores, ou seja, aqueles que podem criar um usuário
   * @return string
   */
  private function getCreatorSelect($creatorId)
  {
    return View::render('partials/creator-select', [
      'creator-options' => self::getCreatorsOptions(),
      'selected-creator_id'=> $creatorId
    ]);
  }

  /**
   * Retorna o input de criador contendo o id do usuário logado
   * @return string
   */
  private function getCreatorInput()
  {
    $loggedUserId = Session::getUserSession()['id'];
    return '<input type="number" class="hidden" name="creator_id" value="' . $loggedUserId . '" />';
  }

  /**
   * Verifica se o usuário está requisitando um formulário de edição
   * @param Request $request
   * @return boolean
   */
  private static function isEditForm($request)
  {
    $uriPartials = explode('/', $request->getUri());
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
    $user = $isEditForm ? User::getUserById($id) : null;
    $modal = $isEditForm ? Modal::getModal(
      'trash',
      'Deletar usuário ' . $user->name,
      'Tem certeza que deseja deletar esse usuário?',
      '/dashboard/user/' . $user->id . '/delete',
      'delete'
    ) : '';

    return View::render('pages/dashboard/user-form', [
      'header' => Layout::getDashboardHeader('user'),
      'title' => $isEditForm ? 'Editar usuário ' . $user->name : 'Adicionar usuário',
      'modal' => $modal,
      'avatar' => $user->avatar ?? 'default.png',
      'name' =>  $user->name ?? '',
      'email' =>  $user->email ?? '',
      'creator_id' =>  $user->creator_id ?? '',
      'hidden' =>  $isEditForm ? 'hidden' : '',
      'selected-user-type' =>  $user->is_admin ?? '',
      'creator-input' => $isEditForm ? self::getCreatorSelect($user->creator_id) : self::getCreatorInput(),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
      'buttons' => self::getFormButtons($isEditForm, $user),
    ]);
  }

  /**
   * Faz o upload do avatar do usuário
   * @param string $avatar
   * @param boolean $canOverride
   * @return mixed
   */
  public static function uploadAvatar($avatar, $previousAvatar = '')
  {
    $file = new File($avatar);
    if ($file->error !== 0 || !in_array($file->extension, ['png', 'jpg', 'jpeg', 'svg'])) {
      return;
    }

    if ($previousAvatar === '') {
      $file->setName();
    } else {
      $file->name = pathinfo($previousAvatar)['filename'];
      $file->extension = pathinfo($previousAvatar)['extension'];
    }

    $file->upload(__DIR__ . '/../../public/uploads/avatars/');
    $file->resizeImage(300, 300);

    return $file->name . '.' . $file->extension;
  }

  /**
   * Adiciona um usuário
   * @param Request $request
   * @param integer $id
   */
  public static function addUser($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());
    $files = $request->getFiles();

    if (!Form::validateInput($postVars)) {
      $router->redirect("/dashboard/user/add/form?status=empty-input");
    }

    if (!Form::validateEmail($postVars['email'])) {
      $router->redirect("/dashboard/user/add/form?status=email-fail");
    }

    if (!empty($postVars['password']) && !Form::validatePassword($postVars['password'])) {
      $router->redirect("/dashboard/user/add/form?status=password-fail");
    }

    if (
      !empty($postVars['password_confirm']) &&
      !Form::validatePasswordConfirm($postVars['password'], $postVars['password_confirm'])
    ) {
      $router->redirect("/dashboard/user/add/form?status=password_confirm-fail");
    }

    if (LoginController::verifyUserExists($postVars['email'])) {
      $router->redirect("/dashboard/user/add/form?status=email-equal");
    }

    $avatar = '';
    if (isset($files) && $files['avatar']['error'] === 0) {
      $avatar = self::uploadAvatar($files['avatar']);
      if (!is_string($avatar)) {
        $router->redirect("/dashboard/user/add/form?status=avatar-fail");
      }
    }

    $user = new User;
    $user->name = $postVars['name'];
    $user->email = $postVars['email'];
    $user->is_admin = $postVars['user-type'];
    $user->creator_id = $postVars['creator_id'];
    $user->avatar = !empty($avatar) ? $avatar : 'default.png';
    $user->password = !empty($postVars['password'])
      ? password_hash($postVars['password'], PASSWORD_DEFAULT)
      : $user->password;

    $user->add();

    $router->redirect("/dashboard/user?status=add-success");
  }

  /**
   * Atualiza um usuário com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function editUser($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());
    $files = $request->getFiles();

    if (!Form::validateInput($postVars, true)) {
      $router->redirect("/dashboard/user/add/form?status=empty-input");
    }

    if (
      !is_numeric($id) ||
      !is_numeric($postVars['creator_id'])
    ) {
      $router->redirect("/dashboard/user/$id/form?status=edit-fail");
    }

    if (!Form::validateEmail($postVars['email'])) {
      $router->redirect("/dashboard/user/$id/form?status=email-fail");
    }

    if (!empty($postVars['password']) && !Form::validatePassword($postVars['password'])) {
      $router->redirect("/dashboard/user/$id/form?status=password-fail");
    }

    if (
      !empty($postVars['password_confirm']) &&
      !Form::validatePasswordConfirm($postVars['password'], $postVars['password_confirm'])
    ) {
      $router->redirect("/dashboard/user/$id/form?status=password_confirm-fail");
    }

    $user = User::getUserById($id);
    if (!$user instanceof User) {
      $router->redirect("/dashboard/user/$id/form?status=edit-fail");
    }

    if ($postVars['email'] != $user->email && LoginController::verifyUserExists($postVars['email'])) {
      $router->redirect("/dashboard/user/$id/form?status=email-equal");
    }

    $avatar = '';
    if (isset($files) && $files['avatar']['error'] === 0) {
      $avatar = self::uploadAvatar(
        $files['avatar'],
        $files['avatar'] != $user->avatar && $user->avatar != 'default.png'
          ? $user->avatar
          : ''
      );
      if (!is_string($avatar)) {
        $router->redirect("/dashboard/user/$id/form?status=avatar-fail");
      }
    }

    $user->name = $postVars['name'];
    $user->email = $postVars['email'];
    $user->is_admin = $postVars['user-type'];
    $user->creator_id = $postVars['creator_id'];
    $user->avatar = !empty($avatar) ? $avatar : $user->avatar;
    $user->password = !empty($postVars['password'])
      ? password_hash($postVars['password'], PASSWORD_DEFAULT)
      : $user->password;

    $user->update();

    $router->redirect("/dashboard/user/$id/form?status=edit-success");
  }

  /**
   * Deleta uma usuário com base em seu ID
   * @param Request $request
   * @param integer $id
   */
  public static function deleteUser($request, $id)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();

    if (!is_numeric($id)) {
      $router->redirect("/dashboard/user/$id/form?status=delete-fail");
    }

    $user = User::getUserById($id);

    if (
      !$user instanceof User ||
      ($user->avatar != 'default.png' && !File::delete(__DIR__ . '/../../public/uploads/avatars/' . $user->avatar))
    ) {
      $router->redirect("/dashboard/user/$id/form?status=delete-fail");
    }

    $user->delete();

    $router->redirect("/dashboard/user?status=delete-success");
  }
}
