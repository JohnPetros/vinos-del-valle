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
   * Atualiza um registro de uva no banco de dados com os dados da instância atual 
   */
  public function update()
  {
    $query = "UPDATE grapes 
              SET name = ?,
                  color_hex = ?
              WHERE id = ?";

    $params = [
      $this->name,
      $this->color_hex,
      $this->id,
    ];

    Database::execute($query, $params);
  }

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
