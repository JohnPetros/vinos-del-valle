<?php

namespace App\utils;

use App\models\Wine;

class Chart
{
  /**
   * Retorna os dados do gráfico de quantidade de vinhos por uva
   * @return array
   */
  public static function getWinesByGrapeChartData()
  {
    $data = [];
    $categories = [];
    $wines = Wine::getWinesAmountByGrape();

    foreach ($wines as $wine) {
      $data[] = $wine['amount'];
      $categories[] = $wine['grape_name'];
    }

    return [
      'id' => 'wines-by-grape',
      'title' => 'Vinhos por uva',
      'color' => '#0079FF',
      'data' =>  join(';', $data),
      'categories' => join(';', $categories),
    ];
  }

  /**
   * Retorna os dados do gráfico de quantidade de vinhos por região
   * @return array
   */
  public static function getWinesByRegionChartData()
  {
    $data = [];
    $categories = [];
    $wines = Wine::getWinesAmountByRegion();

    foreach ($wines as $wine) {
      $data[] = $wine['amount'];
      $categories[] = $wine['region_name'];
    }

    return [
      'id' => 'wines-by-region',
      'title' => 'Vinhos por região',
      'color' => '#1b9c85',
      'data' =>  join(';', $data),
      'categories' => join(';', $categories),
    ];
  }

  /**
   * Retorna os dados do gráfico de quantidade de vinhos por país
   * @return array
   */
  public static function getWinesByCountryChartData()
  {
    $data = [];
    $categories = [];
    $wines = Wine::getWinesAmountByRegion();

    foreach ($wines as $wine) {
      $data[] = $wine['amount'];
      $country = Country::getCountryByCode($wine['country_code']);
      $categories[] = $country->name;
    }

    return [
      'id' => 'wines-by-country',
      'title' => 'Vinhos por país',
      'color' => '#F79327',
      'data' =>  join(';', $data),
      'categories' => join(';', $categories),
    ];
  }
}
