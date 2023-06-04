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
      'title' => 'Quantidade de vinhos por uva',
      'color' => '#8b112e',
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
      'title' => 'Quantidade de vinhos por região',
      'color' => '#0079FF',
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
    $wines = Wine::getWinesAmountByCountry();

    foreach ($wines as $wine) {
      $data[] = $wine['amount'];
      $categories[] = Country::getCountryByCode($wine['country_code'])->name;
    }

    return [
      'id' => 'wines-by-country',
      'title' => 'Quantidade de vinhos por país',
      'color' => '#1b9c85',
      'data' =>  join(';', $data),
      'categories' => join(';', $categories),
    ];
  }

  private static function getWinesAmountByYear($wines, $year)
  {
    return count(array_filter($wines, function ($wine) use ($year) {
      $harvest_year = date('Y', strtotime($wine->harvest_date));
      return $harvest_year == $year;
    }));
  }

  /**
   * Retorna os dados do gráfico de quantidade de vinhos por ano de colheita
   * @return array
   */
  public static function getWinesByHarvestYearChartData()
  {

    $years = Year::getLastYears();
    $wines = Wine::getWines([]);

    $data = array_map(function ($year) use ($wines) {
      return self::getWinesAmountByYear($wines, $year);
    }, $years);

    $categories = $years;

    return [
      'id' => 'wines-by-harvest-year',
      'title' => 'Quantidade de vinhos por ano de colheita',
      'color' => '#ffd93d',
      'data' =>  join(';', $data),
      'categories' => join(';', $categories),
    ];
  }

  /**
   * Retorna os dados do gráfico de quantidade de cada vinho
   * @return array
   */
  public static function getWinesAmountChartData()
  {
    $data = [];
    $categories = [];
    $wines = Wine::getWinesByAmount();

    foreach ($wines as $wine) {
      $data[] = $wine['amount'];
      $categories[] = $wine['name'];
    }

    return [
      'id' => 'wines-by-amount',
      'title' => 'Quantidade de cada vinho',
      'color' => '#9336B4',
      'data' =>  join(';', $data),
      'categories' => join(';', $categories),
    ];
  }
}
