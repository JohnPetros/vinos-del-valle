<?php

namespace App\utils;

class File
{
  /**
   * Nome do arquivo
   * @var string
   */
  public $name;

  /**
   * Extensão do arquivo
   * @var string
   */
  public $extension;

  /**
   * Type do arquivo
   * @var string
   */
  public $type;

  /**
   * Nome/Caminho temporário do arquivo
   * @var string
   */
  public $tmpName;

  /**
   * Código de erro do upload
   * @var integer
   */
  public $error;

  /**
   * Tamanho do arquivo
   * @var integer
   */
  public $size;

  /**
   * Define o nome do arquivo
   * @return boolean
   */
  public function setName()
  {
    $this->name = time() . '-' . rand(1000, 9000) . '-' . uniqid();
  }

  /**
   * Faz o upload do arquivo
   * @param string $dir
   * @return boolean
   */
  public function upload($dir)
  {
    $path = $dir . $this->name . '.' . $this->extension;

    return move_uploaded_file($this->tmpName, $path);
  }

  /**
   * Deleta o arquivo
   * @return boolean
   */
  public static function delete($path)
  {
    if (!file_exists($path)) return false;

    return unlink($path);
  }

  /**
   * Construtor da classe
   * @param array $file $_FILES['index']
   */
  public function __construct($file)
  {
    $this->type = $file['type'];
    $this->tmpName = $file['tmp_name'];
    $this->error = $file['error'];
    $this->size = $file['size'];

    $info = pathinfo($file['name']);
    $this->extension = $info['extension'];
  }
}
