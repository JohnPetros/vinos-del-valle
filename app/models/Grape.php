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
   * Adiciona um registro de uva no banco de dados com os dados da instância atual 
   */
  public function add()
  {
    $query = "INSERT INTO grapes (
                name,
                color_hex
              ) VALUES (?, ?)";

    $params = [
      $this->name,
      $this->color_hex,
    ];

    Database::execute($query, $params);
  }

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
   * Deleta um registro de uva no banco de dados com base no ID da instância atual
   */
  public function delete()
  {
    $query = "DELETE FROM grapes WHERE id = ?";

    Database::execute($query, [$this->id]);
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
