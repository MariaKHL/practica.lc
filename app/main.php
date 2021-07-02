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
    private const FILE_BUILDER = DIR_APP . 'system' . DSE . 'builder.php';

    private const CLASS_BUILDER = System\Builder::class;

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
      $db = new \MongoDB\Client;
      $db = $db->db_kinanet;
      $route = new \App\System\Route\Router;
      $app = $route->startApp($builder, $db);
    }
  }


?>
