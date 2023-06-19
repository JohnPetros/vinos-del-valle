<?php

namespace App\utils;

class Country
{

  /**
   * Retorna todos os dados de países
   * @return array
   */
  public static function getCountries()
  {
    $countriesData = file_get_contents(__DIR__ . '/../../public/data/countries.json');
    $countries = json_decode($countriesData);
    return $countries;
  }

  /**
   * Retorna um país com base em seu código de pais
   * @return stdClass
   */
  public static function getCountryByCode($code)
  {
    $countries = self::getCountries();

    foreach ($countries as $country) {
      if ($country->code === $code) {
        return $country;
      }
    }
  }
}
