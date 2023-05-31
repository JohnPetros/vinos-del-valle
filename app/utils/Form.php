<?php

namespace App\utils;

use App\core\View;

class Form
{
  /**
   * Verifica se o usuário está requisitando um formulário de edição
   * @param Request $request
   * @return boolean
   */
  public static function isEditForm($request)
  {
    $uriPartials =  explode('/', $request->getUri());
    return is_numeric($uriPartials[3]);
  }

  /**
   * Retorna os botões paro formulário com base se é um formulário de edição ou não
   * @param boolean $isEditForm
   * @return string
   */
  public static function getFormButtons($isEditForm, $model)
  {
    $buttons = '';

    if ($isEditForm) {
      $buttons .= View::render('partials/button', [
        'type' => 'edit',
        'title' => 'Editar',
        'value' => '/dashboard/wine/' . $model['obj']->id . '/edit',
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
}
