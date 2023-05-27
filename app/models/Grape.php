<?php

namespace App\models;

use App\core\Database;

class Grape
{
  /**
   * ID da uva
   * @var integer
   */
  public $id;

  /**
   * Nome da uva
   * @var string
   */
  public $name;

  /**
   * Código hexadecimal da cor referente a uva
   * @var string
   */
  public $color_hex;

  /**
   * Retorna dados das uvas
   * @return array
   */
  public static function getGrapes()
  {
    $query = "SELECT * FROM grapes";

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }
}
