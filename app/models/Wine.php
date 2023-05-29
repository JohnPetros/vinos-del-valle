<?php

namespace App\models;

use App\core\Database;

class Wine
{
  /**
   * ID do vinho
   * @var integer
   */
  public $id;

  /**
   * Nome do vinho
   * @var string
   */
  public $name;

  /**
   * Vinícula do vinho
   * @var string
   */
  public $winery;

  /**
   * Nome da região do vinho
   * @var integer
   */
  public $region;

  /**
   * ID da região do vinho
   * @var integer
   */
  public $region_id;

  /**
   * ID da uva do vinho
   * @var integer
   */
  public $region_grape;

  /**
   * Nome da uva do vinho
   * @var string
   */
  public $grape;

  /**
   * Data de colheita do vinho
   * @var string
   */
  public $harvest_date;

  /**
   * Data de envase do vinho
   * @var string
   */
  public $bottling_date;

  /**
   * Data de cadastro do vinho
   * @var string
   */
  public $registration_date;



  /**
   * Retorna os filtradores da query de vinhos
   * @param string $param
   * @param string $value
   * @return string
   */
  private static function getFilters($param, $value)
  {

    if (!is_numeric($value)) return;

    switch ($param) {
      case 'year':
        return "YEAR(W.harvest_date) = $value";
      case 'region':
        return "W.region_id = $value";
      case 'category':
        return "W.grape_id = $value";
      default:
        return;
    }
  }

  /**
   * Retorna os dados de vinhos
   * @param array $params
   * @return array
   */
  public static function getWines($params)
  {
    $query = "SELECT W.id, W.name, W.harvest_date, R.country_code, G.name AS grape, G.color_hex
              FROM wines AS W
              JOIN regions AS R ON R.id = W.region_id
              JOIN grapes AS G ON G.id = W.grape_id";

    if (count($params)) {
      $filters = array_map(
        'self::getFilters',
        array_keys($params),
        array_values($params)
      );
      $filters = array_filter($filters);


      if (count($filters)) {
        $query .= ' WHERE ';
        $query .= join(' AND ', $filters);
      };
    }

    $query .= ' ORDER BY W.harvest_date';

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }

  /**
   * Retorna um vinho com base em seu ID
   * @return Wine
   */
  public static function getWineById($id)
  {
    $query = "SELECT W.id, W.name, W.winery, W.registration_date, W.harvest_date, W.bottling_date,
                     W.region_id, W.grape_id,
                     R.name AS region, R.country_code, G.name AS grape, G.color_hex
              FROM wines AS W
              JOIN regions AS R ON R.id = W.region_id
              JOIN grapes AS G ON G.id = W.grape_id
              WHERE W.id = $id";

    return Database::execute($query)->fetchObject();
  }
}
