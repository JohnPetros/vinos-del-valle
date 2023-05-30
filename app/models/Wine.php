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
  public $grape_id;

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
   * Adiciona um registro de vinho no banco de dados com os dados da instância atual 
   */
  public function add()
  {
    $query = "INSERT INTO wines (
                          name,
                          winery,
                          grape_id,
                          region_id,
                          harvest_date, 
                          bottling_date,
                          registration_date
              ) VALUES
              (?, ?, ?, ?, ?, ?, ?)";

    $params = [
      $this->name,
      $this->winery,
      $this->grape_id,
      $this->region_id,
      $this->harvest_date,
      $this->bottling_date,
      $this->registration_date
    ];
    
    Database::execute($query, $params);
  }

  /**
   * Atualiza o registro de vinho no banco de dados com os dados da instância atual 
   */
  public function update()
  {
    $query = "UPDATE wines 
              SET name = ?,
                  winery = ?,
                  grape_id = ?,
                  region_id = ?,
                  harvest_date = ?, 
                  bottling_date = ?,
                  registration_date = ?
              WHERE id = ?";

    $params = [
      $this->name,
      $this->winery,
      $this->grape_id,
      $this->region_id,
      $this->harvest_date,
      $this->bottling_date,
      $this->registration_date,
      $this->id,
    ];

    Database::execute($query, $params);
  }

  /**
   * Deleta um registro de vinho no banco de dados com base no ID da instância atual
   */
  public function delete()
  {
    $query = "DELETE FROM wines WHERE id = ?";

    Database::execute($query, [$this->id]);
  }

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
              WHERE W.id = ?";

    return Database::execute($query, [$id])->fetchObject(self::class);
  }
}
