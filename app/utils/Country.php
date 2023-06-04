<?php

namespace App\utils;

class Country
{

  public static function getCountries()
  {
    $countriesData = file_get_contents(__DIR__ . '/../../public/data/countries.json');
    $countries = json_decode($countriesData);
    return $countries;
  }

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
