<?php

namespace App\utils;

class Year
{
  public static function getLastYears()
  {
    $currentYear = date('Y');
    $years = [];

    for ($i = 1; $i < 10; $i++) {
      $years[] = --$currentYear;
    }
    return $years;
  }
}
