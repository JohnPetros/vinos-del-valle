<?php

namespace App\models;

use \App\core\Database;

class Region
{
  /**
   * ID da região
   * @var integer
   */
  public $id;

  /**
   * Nome da região
   * @var string
   */
  public $name;

  /**
   * Cidade da região
   * @var string
   */
  public $city;

  /**
   * Estado da região
   * @var string
   */
  public $state;

  /**
   * Código do país da região
   * @var string
   */
  public $country_code;

  /**
   * Adiciona um registro de região no banco de dados com os dados da instância atual 
   */
  public function add()
  {
    $query = "INSERT INTO regions (
                name,
                city,
                state,
                country_code
              ) VALUES (?, ?, ?, ?)";

    $params = [
      $this->name,
      $this->city,
      $this->state,
      $this->country_code,
    ];

   
    Database::execute($query, $params);
  }

  /**
   * Atualiza o registro de região no banco de dados com os dados da instância atual 
   */
  public function update()
  {
    $query = "UPDATE regions 
              SET name = ?,
                  city = ?,
                  state = ?,
                  country_code = ?
              WHERE id = ?";

    $params = [
      $this->name,
      $this->city,
      $this->state,
      $this->country_code,
      $this->id,
    ];

    Database::execute($query, $params);
  }

  /**
   * Retorna as regiões do banco de dados
   * @param string $countryCode
   */
  public static function getRegions($countryCode = null)
  {
    $query = "SELECT * FROM regions";

    if ($countryCode) {
      $query .= " WHERE country_code = ?";
    }

    return Database::execute($query, [$countryCode])->fetchAll(\PDO::FETCH_CLASS, self::class);
  }

  /**
   * Retorna uma região do banco de dados com base em seu ID
   * @return Wine
   */
  public static function getRegionById($id)
  {
    $query = "SELECT * FROM regions WHERE id = ?";

    return Database::execute($query, [$id])->fetchObject(self::class);
  }
}
