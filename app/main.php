<?php
  /**
  * @package App\Main
  * main.php - main application file
  *
  * @version 1.0.0
  * @link https://github.com/MariaKHL/practica.lc.git
  * @copyright 2021 Khlystun Maria
  * @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
  * @NOTE: INNER DOC LANGUAGE IS RU;
  */

  namespace App;

  if (!defined('DSE') || !defined('DIR_ROOT')) { exit('Access denied!'); }

  final class Main
  {
    /**
    * @var string FILE_BUILDER Путь до автозагрузчика
    */
    private const FILE_BUILDER = DIR_APP . 'system' . DSE . 'builder.php';

    /**
    * @var string CLASS_BUILDER Имя класса автозагрузчика
    */
    private const CLASS_BUILDER = System\Builder::class;


    /**
    * @method __construct(@param null|object $params = NULL)
    * @param null|object $params = NULL Загрузочные параметры.
    * @todo Загружает и инициирует автозагрузчик приложения,
    * а также загружает приложение, к которому обратился клиент.
    * @since 1.0.0
    * @return void
    * @throws Если файл автозагрузчика не найден.
    */
    function __construct(?object $params = NULL)
    {
      if (@is_file(self::FILE_BUILDER)) {
        require_once self::FILE_BUILDER;
      }else {
        throw new \Exception(__CLASS__ . ": system builder not found!", 1);
      }

      if (class_exists(self::CLASS_BUILDER)) {
        $class = self::CLASS_BUILDER;
        $builder = new $class();
      }else {
        throw new \Exception(__CLASS__ . ": class of system builder not found!", 1);
      }

      $builder->system;
      $db = new \MongoDB\Client(
        'mongodb://95.214.63.87:27017',
        [
          'username' => 'maria',
          'password' => 'T2gSBwk*s',
          // 'ssl' => true,
          'authSource' => 'db_kinanet',
        ]
      );
      $db = $db->db_kinanet;
      $route = new \App\System\Route\Router;
      $app = $route->startApp($builder, $db);
    }
  }


?>
