<?php
/**
* @package index
* index.php - application entry point
*
* @version 1.0.0
* @link https://github.com/MariaKHL/practica.lc.git
* @copyright 2021 Khlystun Maria
* @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
* @NOTE: INNER DOC LANGUAGE IS RU;
*/


  try {
    /**
    * @var string DSE Системный разделитель пути.
    */
    define('DSE', DIRECTORY_SEPARATOR);

    /**
    * @var string DIR_ROOT Абсолютный системный путь до корня проекта.
    */
    define('DIR_ROOT', __DIR__ . DSE);

    /**
    * @var string DIR_APP Абсолютный системный путь до корня проекта.
    */
    define('DIR_APP', DIR_ROOT . 'app' . DSE);

    /**
    * @var string FILE_MAIN Абсолютный системный путь до главного файла приложения.
    */
    define('FILE_MAIN', DIR_APP . 'main.php');

    /**
    * @var string CLASS_MAIN Имя класса главного файла приложения.
    */
    define('CLASS_MAIN', \App\Main::class);

    if (@is_file(FILE_MAIN)) {
      require_once FILE_MAIN;
    }else {
      throw new \Exception("Index: main file not found or path was redefined!", 1);
    }

    if ( class_exists(CLASS_MAIN)) {
      $class = CLASS_MAIN;
      $main = new $class();
    }else {
      throw new \Exception("Index: main class not found or was redefined!", 1);
    }
  } catch (\Exception $e) {
    print($e->getMessage());
    exit;
  }


?>
