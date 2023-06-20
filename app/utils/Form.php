<?php

namespace App\utils;

class Form
{

  /**
   * Limpa a entrada de dados do usuário
   * @param array $inputData
   * @return array
   */
  public static function cleanInput($input)
  {
    $input = array_map('trim', $input);
    $input = array_map('stripslashes', $input);
    return $input;
  }

  /**
   * Valida uma senha
   * @param string $password
   * @return boolean
   */
  public static function validatePassword($password)
  {
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s])[A-Za-z\d\W\S]{6,}$/';
    return preg_match($passwordRegex, $password);
  }

  /**
   * Valida se a confirmação de senha corresponde a senha
   * @param string $password
   * @return boolean
   */
  public static function validatePasswordConfirm($password, $passwordConfirm)
  {
    return $password === $passwordConfirm;
  }

  /**
   * Valida um e-mail
   * @param string $email
   * @return boolean
   */
  public static function validateEmail($email)
  {
    $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    return preg_match($emailRegex, $email);
  }

  /**
   * Valida um imagem
   * @param string $imageExtension
   * @return boolean
   */
  public static function validateImage($imageExtension)
  {
    return in_array($imageExtension, ['png', 'jpg', 'jpeg', 'svg']);
  }

  /**
   * Valida um ano, verificando ele está na faixa de 2014 e 2023
   * @param string $date
   * @return boolean
   */
  public static function validateYear($date)
  {
    $year = date('Y', strtotime($date));
    $acceptedYears = Year::getLastYears();
    
    return in_array($year, $acceptedYears);
  }

  /**
   * Valida a entrada de dados do usuário está vazia ou não
   * @param array $input
   * @param boolean $hasExceptions
   * @return boolean
   */
  public static function validateInput($input, $hasExceptions = false)
  {
    $execptions = $hasExceptions ? ['password', 'password_confirm'] : [];

    foreach ($input as $key => $value) {
      if ($value == '' && !in_array($key, $execptions)) {
        return false;
      }
    }
    return true;
  }
}
