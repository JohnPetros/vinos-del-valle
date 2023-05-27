<?php

namespace App\utils;

use \App\core\View;

class Modal
{
  /**
   * Retorna o Modal com seus dados
   * @param string $title
   * @param string $description
   * @param string $action
   * @param string $id
   * @return string
   */
  public static function getModal($icon, $title, $description, $action, $id)
  {
    return View::render('partials/modal', [
      'icon' => $icon,
      'title' => $title,
      'description' => $description,
      'action' => $action,
      'id' => $id,
    ]);
  }
}
