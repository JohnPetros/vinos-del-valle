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
