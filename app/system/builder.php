<?php
  /**
  * @package App\System\Builder
  * builder.php - app builder
  *
  * @version 1.0.0
  * @link https://github.com/MariaKHL/practica.lc.git
  * @copyright 2021 Khlystun Maria
  * @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
  * @NOTE: INNER DOC LANGUAGE IS RU;
  */

  namespace App\System;

  if (!defined('DSE') || !defined('DIR_ROOT')) { exit('Access denied!'); }


  final class Builder
  {
    private object $lists;


    function __construct()
    {
      $this->lists = self::scanDir(__DIR__ . DSE . 'autoload' . DSE);
    }

    public function __get(string $name) : void
    {
      if (isset($this->lists->{$name}) && @is_file($this->lists->{$name})) {
        $list = json_decode( json_encode( yaml_parse_file( $this->lists->{$name} ) ) );
        if (!empty($list)) {
          foreach ($list as $package => $path) {
            self::importOnce($path);
          }
        }
      }else {
        throw new \Exception(__CLASS__ . ": autoload list with name $name not found!", 1);
      }
    }

    /**
    * @method preparePath(@param string $path)
    * @param string $path путь для преобразования в системный.
    * @todo Преобразует путь из шаблонного в системный.
    * @since 1.0.0
    * @return string
    * @throws Если по итоговому пути файл не найден.
    */
    public static function preparePath(string $path) : string
    {
      $save = $path;
      $temp = (object) [
        '@root/' => DIR_ROOT,
        '@app/' => DIR_APP,
        '@mvc/'  => DIR_APP . 'mvc' . DSE,
        '@system/'  => DIR_APP . 'system' . DSE,
        '/' => DSE,
        '\\' => DSE,
      ];
      foreach ($temp as $tmp => $sys_path) {
        $path = str_replace($tmp, $sys_path, $path);
      }

      if (@is_file($path)) {
        return $path;
      }else {
        throw new \Exception(__CLASS__ . ": file by template $save not found!", 1);
      }
    }


    /**
    * @method import(@param string $path)
    * @param string $path путь до импортируемого файла.
    * @todo Производит импорт php фала в проект, позволяя использовать шаблоны путей.
    * @since 1.0.0
    * @return mixed
    */
    public static function import(string $path)
    {
      return require self::preparePath($path);
    }

    /**
    * @method importOnce(@param string $path)
    * @see @method import(@param string $path)
    * @since 1.0.0
    * @NOTE: позволяет импортировать файл php только однажды.
    */
    public static function importOnce(string $path)
    {
      return require_once self::preparePath($path);
    }


    /**
    * @method  scanDir(@param string $path)
    * @param string $path Путь до сканируемого каталога/директории
    * @todo Производит рекурсивное сканирование директории с файлами.
    * @since 1.0.0
    * @return object
    * @throws Если неверно указан исходный путь.
    */
    public static function scanDir(string $path) : object
    {
      $result = (object) [];
      $dir = (object) scandir($path);
      foreach ($dir as $num => $value) {
        if ($num > 1) {
          if (@is_file($path . $value)) {
            $result->{explode('.', $value)[0]} = $path . $value;
          }elseif (is_dir($path . $value)) {
            $result->{$value} = self::scanDir($path . $value . DSE);
          }else {
            throw new \Exception(__CLASS__ . ": dir or file with name $value not found!", 1);
          }
        }
      }
      return $result;
    }
  }

?>
