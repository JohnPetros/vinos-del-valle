<?php

namespace App\controllers;

use App\core\Session;
use App\core\View;
use App\utils\Layout;
use App\utils\Toast;

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
      case 'edit-sucess':
        return Toast::getSuccess('Perfil editado');
      case 'edit-fail':
        return Toast::getSuccess('Erro ao editar o perfil');
      default:
        return Toast::getError('Escreva uma mensagem no toast...');
    }
  }

  public static function getProfilePage($request)
  {
    Session::verifyLoggedUser('login', 'admin', $request);

    return View::render('pages/dashboard/profile', [
      'header' => Layout::getDashboardHeader(''),
      'toast' => isset($params['status']) ? self::getToast($params['status']) : '',
    ]);
  }
}
