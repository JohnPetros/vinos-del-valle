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
use App\controllers\LoginController;

class ProfileController
{

  /**
   * Retorna o toast junto com a mensagem criada com base no status
   * @param string $status
   * @return string
   */
  private static function getToast($status)
  {
    switch ($status) {
      case 'edit-success':
        return Toast::getSuccess('Perfil editado');
      case 'edit-fail':
        return Toast::getError('Erro ao editar o perfil');
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
      default:
        return Toast::getError('Escreva uma mensagem no toast...');
    }
  }

  public static function getProfilePage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $params = $request->getQueryParams();
    $loggedUser = Session::getUserSession();

    $modal = Modal::getModal(
      'warning-circle',
      'Deletar perfil',
      'TEM CERTEZA MESMO QUE DESEJA DELETAR SUA CONTA!?',
      '/dashboard/user/' . $loggedUser['id'] . '/delete',
      'delete'
    );

    return View::render('pages/dashboard/profile', [
      'header' => Layout::getDashboardHeader(''),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
      'name' => $loggedUser['name'],
      'email' => $loggedUser['email'],
      'avatar' => $loggedUser['avatar'],
      'modal' => $modal,
    ]);
  }

  /**
   * Faz o upload do avatar do usuário logado
   * @param string $file
   * @return string|boolean
   */
  private function uploadAvatar($avatar)
  {
    $file = new File($avatar);

    if ($file->error !== 0 || !Form::validateImage($file->extension)) {
      return false;
    }

    $file->upload(__DIR__ . '/../../public/uploads/avatars/');

    return $file->name . '.' . $file->extension;
  }

  /**
   * Atualiza os dados do usuário logado
   * @param Request $request
   * @param integer $id
   */
  public static function editProfile($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    $router = $request->getRouter();
    $postVars = Form::cleanInput($request->getPostVars());
    $files = $request->getFiles();
    $loggedUser = Session::getUserSession();

    if (!Form::validateInput($postVars, true)) {
      $router->redirect("/dashboard/profile?status=empty-input");
    }

    if (
      !isset($loggedUser['id']) ||
      !is_numeric($loggedUser['id'])
    ) {
      $router->redirect("/dashboard/profile?status=edit-fail");
    }

    if (!Form::validateEmail($postVars['email'])) {
      $router->redirect("/dashboard/profile?status=email-fail");
    }

    if (!empty($postVars['password']) && Form::validatePassword($postVars['password'])) {
      $router->redirect("/dashboard/profile?status=password-fail");
    }

    if (
      !empty($postVars['password_confirm']) &&
      !Form::validatePasswordConfirm($postVars['password'], $postVars['password_confirm'])
    ) {
      $router->redirect("/dashboard/profile?status=password_confirm-fail");
    }

    $avatar = '';
    if (isset($files) && $files['avatar']['error'] === 0) {
      $avatar = self::uploadAvatar($files['avatar'], $router);
      if (is_bool($avatar)) {
        $router->redirect("/dashboard/profile?status=avatar-fail");
      }
    }

    $user = User::getUserById($loggedUser['id']);

    if (!$user instanceof User) {
      $router->redirect("/dashboard/profile?status=edit-fail");
    }

    if (
      $user->email !==  $postVars['email'] && 
      LoginController::verifyUserExists($postVars['email'])
    ) {
      $router->redirect("/dashboard/profile?status=email-fail");
    }

    $user->name = $postVars['name'];
    $user->email = $postVars['email'];
    $user->avatar = !empty($avatar) ? $avatar : $user->avatar;
    $user->password = !empty($postVars['password'])
      ? password_hash($postVars['password'], PASSWORD_DEFAULT)
      : $user->password;

    $user->update();

    $router->redirect("/dashboard/profile?status=edit-success");
  }
}
