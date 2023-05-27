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
   * Retorna as regiões que servirão como opções para um select
   */
  public static function getRegions()
  {
    $query = "SELECT id, name, country_code FROM regions";

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }
}
