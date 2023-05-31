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
   * Retorna todos os registros de uvas do banco de dados
   * @return array
   */
  public static function getGrapes()
  {
    $query = "SELECT * FROM grapes";

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }

  /**
   * Retorna uma uva do banco de dados com base em seu ID
   * @return Region
   */
  public static function getGrapeById($id)
  {
    $query = "SELECT * FROM grapes WHERE id = ?";

    return Database::execute($query, [$id])->fetchObject(self::class);
  }
}
