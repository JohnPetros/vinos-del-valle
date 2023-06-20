<?php

namespace App\utils;

class Year
{
  /**
   * Retorna os últimos 10 anos
   * @return array
   */
  public static function getLastYears()
  {
    $currentYear = date('Y');
    $years = [];

    for ($i = 1; $i <= 10; $i++) {
      $years[] = $currentYear;
      --$currentYear;
    }
    
    return $years;
  }
}
